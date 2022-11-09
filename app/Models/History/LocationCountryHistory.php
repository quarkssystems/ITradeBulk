<?php

namespace App\Models\History;

use App\Models\LocationCountry;
use App\Models\Support\HistoryModelSupport;

class LocationCountryHistory extends LocationCountry
{
    use HistoryModelSupport;
    protected $table = "location_country_histories";
}
