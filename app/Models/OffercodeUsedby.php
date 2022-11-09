<?php

namespace App\Models;

use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OffercodeUsedby extends Model
{
    use BaseModelSupport;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $perPage = 20;
    protected $table = 'offercode_usedby_orders';
    /**
     * The attributes that are mass assofferdealsignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'order_id',
        'user_id',
        'offer_id',
    ];

    /**
     * @var array
     */
    public $casts = [
       'uuid' => 'string',
        'order_id' => 'string',
        'user_id' => 'string',
        'offer_id'=> 'string'
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
            $model->createUserLog($model, 'userActivity.offercodeUsedby|created');
            //$model->generateHistory($model);
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
   /* public function history() : HasMany
    {
        return $this->hasMany(OfferDealsHistory::class, "history_of", 'uuid');
    }
  */

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

}
