<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransporterTradingArea extends Model
{
    protected $fillable = [
        'user_id',
        'trading_area',
        'area_id',
        'transporter_vehicle_id'
    ];
}
