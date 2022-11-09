<?php

namespace App\Models\History;

use App\Models\Support\HistoryModelSupport;
use App\Models\VehicleCapacity;
use Illuminate\Database\Eloquent\Model;

class VehicleCapacityHistory extends VehicleCapacity
{
    use HistoryModelSupport;
    protected $table = "vehicle_capacity_histories";
}
