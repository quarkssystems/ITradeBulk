<?php

namespace App\Models;

use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use BaseModelSupport;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $perPage = 20;

    const TRADER = 'TRADER';
    const SUPPLIER = 'SUPPLIER';
    const DRIVER = 'DRIVER';
    const ADMIN = 'ADMIN';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'order_id',
        'notification',
        'type'
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'user_id' => 'string',
        'order_id' => 'string',
        'notification' => 'string',
        'type' => 'string'
    ];

    /**
     * Boot Method
     * @return void
     */
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
//            $model->checkSlug($model);
        });

        /**
         * @see BaseModelSupport::createUserActivity()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.notification|created');
//            $model->generateHistory($model);
        });

    }

 
  
}