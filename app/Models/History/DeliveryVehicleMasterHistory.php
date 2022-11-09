<?php

namespace App\Models\History;

use App\Models\DeliveryVehicleMaster;
use App\Models\Support\HistoryModelSupport;
use Illuminate\Database\Eloquent\Model;

class DeliveryVehicleMasterHistory extends DeliveryVehicleMaster
{
    use HistoryModelSupport;
    protected $table = "delivery_vehicle_master_histories";
}
