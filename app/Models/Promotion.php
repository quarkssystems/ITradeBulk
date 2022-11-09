<?php

namespace App\Models;

use App\Models\History\PromotionHitory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use BaseModelSupport, SoftDeletes;

    protected $dates = ['deleted_at'];
    // protected $dates = ['deleted_at', 'period_from', 'period_to'];
    protected $perPage = 20;

    protected $fillable = [
        'uuid',
        'promotion_id',
        'promotion_type',
        'period_from',
        'period_to',
        'promotion_price',
        'user_id',
        'product_id',
        'store_id',
        'current_price'
    ];

    // public $casts = [
    //     'uuid' => 'string',
    //     'promotion_id' => 'string',
    //     'promotion_type' => 'string',
    //     'period_from' => 'date',
    //     'period_to' => 'date',
    //     'promotion_price' => 'float',
    // ];

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
            $model->createUserLog($model, 'userActivity.promotion|created');
            $model->generateHistory($model);
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
                $model->createUserLog($model, 'userActivity.promotion|updated');
            }
        });

        static::deleting(function ($model) {
            $model->deleteHistory($model);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(PromotionHitory::class, "history_of", 'uuid');
    }

    public function products()
    {
        return $this->hasOne(Product::class, 'uuid', 'product_id');
    }

    public function supplierCompany()
    {
        return $this->hasOne(UserCompany::class, "owner_user_id", 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, "products_id", 'uuid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", 'uuid');
    }

    public function scopeUserID($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function scopeActivePromotionn($query)
    {
        return $query->whereDate('promotions.period_from', '<=', date("Y-m-d"))
            ->whereDate('promotions.period_to', '>=', date("Y-m-d"));
        // return $query->where('user_id', $user_id);
    }
}
