<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRejectNotification extends Model
{
    protected $table = 'user_reject_notifications';

    protected $fillable = ['user_id','order_id','reject_reason'];
}