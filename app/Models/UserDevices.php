<?php

namespace App\Models;

use App\Models\Support\BaseModelSupport;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UserDevices extends Model
{
    use BaseModelSupport;

    protected $fillable = [
        'uuid',
        'user_id',
        'device_id',
        'device_token',
        'device_type',
        'device_os',
        'device_model',
        'player_id'
    ];

    protected static function boot() : void
    {
        parent::boot();

        /**
         * @see BaseModelSupport::addUUID()
         * @see User::setUserType()
         * @see User::setClientId()
         */
        static::creating(function ($model) {
            $model->addUUID($model);
        });
    }

}
