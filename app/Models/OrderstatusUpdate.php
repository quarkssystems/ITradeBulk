<?php

namespace App\Models;

use App\Models\History\OrderstatusUpdateHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderstatusUpdate extends Model
{
    use BaseModelSupport;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $perPage = 20;
    protected $table = 'orderstatus_updates';
    /**
     * The attributes that are mass assofferdealsignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'sales_id',
        'user_id',
        'order_status',
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'sales_id' => 'string',
        'user_id' => 'string',
        'order_status' => 'string'
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
           // $model->checkSlug($model);
        });

        /**
         * @see BaseModelSupport::createUserActivity()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.orderstatus_updates|created');
            $model->generateHistory($model);
        });

        static::updating(function ($model) {
            
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserActivity()
         */
        static::updated(function($model){
            if(!auth()->guest())
            {
                $model->generateHistory($model);
                $model->createUserLog($model, 'userActivity.orderstatus_updates|updated');
            }
        });

        static::deleting(function ($model){
            $model->deleteHistory($model);
        });
    }

    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history() : HasMany
    {
        return $this->hasMany(OrderstatusUpdateHistory::class, "history_of", 'uuid');
    }
   
  
}
