<?php

namespace App\Models\History;

use App\Models\LocationZipcode;
use App\Models\Support\HistoryModelSupport;

class LocationZipcodeHistory extends LocationZipcode
{
    use HistoryModelSupport;
    protected $table = "location_zipcode_histories";
}
