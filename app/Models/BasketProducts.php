<?php

namespace App\Models;

use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BasketProducts extends Model
{
    use BaseModelSupport;

    protected $perPage = 20;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'basket_id',
        'product_id',
        'single_qty',
        'shrink_qty',
        'case_qty',
        'pallet_qty',
        'color',
        'size'
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'basket_id' => 'string',
        'product_id' => 'string',
        'single_qty' => 'integer',
        'shrink_qty' => 'integer',
        'case_qty' => 'integer',
        'pallet_qty' => 'integer'
    ];

    protected $attributes = [
        'single_qty' => 1,
    ];

    /**
     * Boot Method
     * @return void
     */
    protected static function boot(): void
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
            $model->createUserLog($model, 'userActivity.basketProduct|created');
            //            $model->generateHistory($model);
        });

        static::updating(function ($model) {
            //            $model->checkSlugUpdate($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserActivity()
         */
        static::updated(function ($model) {
            if (!auth()->guest()) {
                //                $model->generateHistory($model);
                $model->createUserLog($model, 'userActivity.basketProduct|updated');
            }
        });

        static::deleting(function ($model) {
            //            $model->deleteHistory($model);
        });
    }

    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'uuid');
    }

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

    public function createNewBasket()
    {
        return $this->create([
            'user_id' => auth()->check() ? auth()->user()->uuid : null
        ]);
    }
}
