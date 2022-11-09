<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliverySchedule extends Model
{
    protected $fillable = ['order_id','driver_id','slot_booked','slot_booked_date','slot_booked_from_time','slot_booked_to_time','status'];

}