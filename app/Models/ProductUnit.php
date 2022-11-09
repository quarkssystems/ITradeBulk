<?php

namespace App\Models;

use App\Models\History\ProductUnitHistory;
use App\Models\History\TaxHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ProductUnit extends Model
{
    use BaseModelSupport;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $perPage = 20;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'name',
        'unit'
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'unit' => 'string'
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
        });

        /**
         * @see BaseModelSupport::createUserActivity()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.productUnit|created');
            $model->generateHistory($model);
        });

        static::updating(function ($model) {
            // $model->checkSlugUpdate($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserActivity()
         */
        static::updated(function($model){
            if(!auth()->guest())
            {
                $model->generateHistory($model);
                $model->createUserLog($model, 'userActivity.productUnit|updated');
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
        return $this->hasMany(ProductUnitHistory::class, "history_of", 'uuid');
    }


    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

    public function getDropDown($except = null)
    {
        return $this->select(DB::raw('CONCAT(name,"|", unit) AS data_key'), DB::raw('CONCAT(name," in ", unit) AS value'))->pluck('value', 'data_key');
    }
}
