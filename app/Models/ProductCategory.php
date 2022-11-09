<?php

namespace App\Models;

use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCategory extends Model
{
    use BaseModelSupport;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'product_id',
        'category_id'
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'product_id' => 'string',
        'category_id' => 'string',
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
        });

        /**
         * @see BaseModelSupport::createUserActivity()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.productCategory|created');
        });

        static::updating(function ($model) {
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserActivity()
         */
        static::updated(function ($model) {
            if (!auth()->guest()) {
                $model->generateHistory($model);
                $model->createUserLog($model, 'userActivity.productCategory|updated');
            }
        });

        static::deleting(function ($model) {
        });
    }

    /*==================================================*/
    /* Relational Model */
    /*==================================================*/


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id", 'uuid');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, "category_id", 'uuid');
    }

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/
}
