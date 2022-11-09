<?php

namespace App\Models;

use App\Models\History\OfferDealsHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Models\Product;

class OfferDeals extends Model
{
    use BaseModelSupport;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $perPage = 20;
    protected $table = 'offerdeals';
    /**
     * The attributes that are mass assofferdealsignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'start_date',
        'end_date',
        'brands_id',
        'categories_id',
        'products_id',
        'offer_method',
        'offer_type',
        'offer_value',
        'description',
        'image',
        'status',
        'offercode',
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'user_id' => 'string',
        'title' => 'string',
        'start_date' => 'string',
        'end_date' => 'string',
        'brands_id' => 'string',
        'categories_id' => 'string',
        'products_id' => 'string',
        'offer_type' => 'string',
        'offer_value' => 'string',
        'description' => 'string',
        'image' => 'string',
        'status' => 'string',
        'offercode' =>  'string',
    ];

    protected $appends = [
        'grid_image',
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
            // $model->checkSlug($model);
        });

        /**
         * @see BaseModelSupport::createUserActivity()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.brand|created');
            $model->generateHistory($model);
        });

        static::updating(function ($model) {
            // $model->checkSlugUpdate($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserActivity()
         */
        static::updated(function ($model) {
            if (!auth()->guest()) {
                $model->generateHistory($model);
                $model->createUserLog($model, 'userActivity.brand|updated');
            }
        });

        static::deleting(function ($model) {
            $model->deleteHistory($model);
        });
    }

    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(OfferDealsHistory::class, "history_of", 'uuid');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function supplierCompany(): HasOne
    {
        return $this->hasOne(UserCompany::class, "owner_user_id", 'user_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "products_id", 'uuid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", 'uuid');
    }
    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

    public function getDropDown($except = null)
    {
        return $this->where('uuid', '!=', $except)->orderBy('name')->pluck('name', 'uuid');
    }

    public function getOfferTypesDropDown()
    {

        return $offertype = [
            'PERCENTAGE' => 'PERCENTAGE',
            'RENT' => 'RAND'
        ];
    }
    public function getOfferMethodDropDown()
    {

        return $offermethod = [
            'PRODUCT OFFER' => 'PRODUCT OFFER',
            'COUPON CODE' => 'COUPON CODE'
        ];
    }
    public function getGridImageAttribute()
    {
        if (is_null($this->icon_file)) {
            return NULL;
        }

        return "<a href='{$this->icon_file}' data-fancybox='gallery' title='{$this->name}' class='grid-thumb-image'><img src='{$this->icon_file}' style='max-width: 100px' /></a>";
    }

    public function scopeUserID($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function getSuppilerNameAttribute()
    {
        return $this->user->first_name;
    }
}
