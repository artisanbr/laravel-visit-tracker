<?php

namespace PragmaRX\Tracker\Vendor\Laravel;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use PragmaRX\Support\GeoIp\GeoIp;
use PragmaRX\Support\PhpSession;
use PragmaRX\Support\ServiceProvider as PragmaRXServiceProvider;
use PragmaRX\Tracker\Data\Repositories\Agent;
use PragmaRX\Tracker\Data\Repositories\Connection;
use PragmaRX\Tracker\Data\Repositories\Cookie;
use PragmaRX\Tracker\Data\Repositories\Device;
use PragmaRX\Tracker\Data\Repositories\Domain;
use PragmaRX\Tracker\Data\Repositories\Error;
use PragmaRX\Tracker\Data\Repositories\Event;
use PragmaRX\Tracker\Data\Repositories\EventLog;
use PragmaRX\Tracker\Data\Repositories\GeoIp as GeoIpRepository;
use PragmaRX\Tracker\Data\Repositories\Language;
use PragmaRX\Tracker\Data\Repositories\Log;
use PragmaRX\Tracker\Data\Repositories\Path;
use PragmaRX\Tracker\Data\Repositories\Query;
use PragmaRX\Tracker\Data\Repositories\QueryArgument;
use PragmaRX\Tracker\Data\Repositories\Referer;
use PragmaRX\Tracker\Data\Repositories\Route;
use PragmaRX\Tracker\Data\Repositories\RoutePath;
use PragmaRX\Tracker\Data\Repositories\RoutePathParameter;
use PragmaRX\Tracker\Data\Repositories\Session;
use PragmaRX\Tracker\Data\Repositories\SqlQuery;
use PragmaRX\Tracker\Data\Repositories\SqlQueryBinding;
use PragmaRX\Tracker\Data\Repositories\SqlQueryBindingParameter;
use PragmaRX\Tracker\Data\Repositories\SqlQueryLog;
use PragmaRX\Tracker\Data\Repositories\SystemClass;
use PragmaRX\Tracker\Data\RepositoryManager;
use PragmaRX\Tracker\Eventing\EventStorage;
use PragmaRX\Tracker\Repositories\Message as MessageRepository;
use PragmaRX\Tracker\Services\Authentication;
use PragmaRX\Tracker\Support\Cache;
use PragmaRX\Tracker\Support\CrawlerDetector;
use PragmaRX\Tracker\Support\Exceptions\Handler as TrackerExceptionHandler;
use PragmaRX\Tracker\Support\LanguageDetect;
use PragmaRX\Tracker\Support\MobileDetect;
use PragmaRX\Tracker\Support\UserAgentParser;
use PragmaRX\Tracker\Tracker;
use PragmaRX\Tracker\Vendor\Laravel\Artisan\Tables as TablesCommand;
use PragmaRX\Tracker\Vendor\Laravel\Artisan\UpdateGeoIp;

class VisitTrackerServiceProvider extends ServiceProvider
{
    protected string $packageVendor = 'artisan-labs';

    protected string $packageName = 'laravel-visit-tracker';

    protected string $packageNameCapitalized = 'VisitTracker';

    protected bool $repositoryManagerIsBooted = false;

    private $config_path = __DIR__ . "/config/";
    private $resources_path = __DIR__ . "/resources/";
    private $views_path = __DIR__ . "/resources/views/";

    private $config_files = [
        "tracker"
    ];


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //Configs
        foreach ($this->config_files as $config_file) {
            $this->mergeConfigFrom(
                $this->config_path.$config_file.".php", $config_file
            );
        }

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        Artisan::call('vendor:publish --provider="PragmaRX\Tracker\Vendor\Laravel\ServiceProvider"');
        Artisan::call('vendor:publish --provider="Shetabit\Visitor\Provider\VisitorServiceProvider"');

        //Configs
        foreach ($this->config_files as $config_file) {
            $this->publishes([
                                 $this->config_path.$config_file.".php" => config_path($config_file.".php"),
                             ], 'config');
        }

        //Views
        /*if($this->app->has('view')){
            $this->loadViewsFrom($this->views_path, 'router');

            $this->publishes([
                                 $this->views_path => resource_path('views/vendor/router'),
                             ], 'views');
        }*/

        /*if(file_exists(base_path('routes/api_generated.php'))){
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api_generated.php'));
        }*/
    }
}
