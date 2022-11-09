<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Support\BaseModelSupport;

class Courier extends Model
{
    use BaseModelSupport;

    protected $fillable = [
        'name',
        'account',
        'link_to_portal',
        'address',
        'default_courier',
        'delivery_option',
        'upload_option_pic',
        'std_lead_time',
        'courier_lead_time',
        'delivery_markup',
        'min_delivery_fee',
        'status',
        'is_own',
        'own_user_id'

    ];
}
