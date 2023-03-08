<?php

namespace ArtisanLabs\LaravelVisitTracker;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class VisitTrackerServiceProvider extends ServiceProvider
{
    protected string $packageVendor = 'artisan-labs';

    protected string $packageName = 'laravel-visit-tracker';

    protected string $packageNameCapitalized = 'VisitTracker';

    protected bool $repositoryManagerIsBooted = false;

    private $config_path    = __DIR__ . "/config/";
    private $migration_path = __DIR__ . "/database/migrations/";

    private $config_files = [
        "tracker",
        "visitor",
    ];

    public function boot()
    {
        /*
                Artisan::call('vendor:publish --provider="PragmaRX\Tracker\Vendor\Laravel\ServiceProvider"');
                Artisan::call('vendor:publish --provider="Shetabit\Visitor\Provider\VisitorServiceProvider"');*/

        //Configs
        foreach ($this->config_files as $config_file) {
            $this->publishes([
                                 $this->config_path . $config_file . ".php" => config_path($config_file . ".php"),
                             ], 'config');
        }

        $this->publishes([
                             $this->migration_path => base_path('database' . DIRECTORY_SEPARATOR . 'migrations'),
                         ], 'migrations'
        );

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

    public function register()
    {
        //Configs
        foreach ($this->config_files as $config_file) {
            $this->mergeConfigFrom(
                $this->config_path . $config_file . ".php", $config_file
            );
        }

        $this->app->singleton('shetabit-visitor', function () {
            $request = app(Request::class);

            return new Visitor($request, config('visitor'));
        });

    }

}
