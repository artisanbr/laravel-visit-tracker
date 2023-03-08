<?php

namespace ArtisanLabs\LaravelVisitTracker\Models;

use Illuminate\Database\Eloquent\Model;
use Shetabit\Visitor\Models\Visit as VisitBase;

class Visit extends VisitBase
{
    public function __construct(array $attributes = [])
    {
        $this->setConnection(config('visitor.connection'));

        parent::__construct($attributes);
    }

}
