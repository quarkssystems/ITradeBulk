<?php

namespace App\Models\History;

use App\Models\LocationCity;
use App\Models\Support\HistoryModelSupport;

class LocationCityHistory extends LocationCity
{
    use HistoryModelSupport;
    protected $table = "location_city_histories";
}
