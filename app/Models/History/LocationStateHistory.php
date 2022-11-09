<?php

namespace App\Models\History;

use App\Models\LocationState;
use App\Models\Support\HistoryModelSupport;

class LocationStateHistory extends LocationState
{
    use HistoryModelSupport;
    protected $table = "location_state_histories";
}
