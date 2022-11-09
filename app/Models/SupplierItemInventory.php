<?php

namespace App\Models;

use App\Models\Support\BaseModelSupport;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class SupplierItemInventory extends Model
{
    use SoftDeletes;
    use BaseModelSupport;

    protected $fillable = [
        'uuid',
        'user_id',
        'product_id',
        'store_id',
        'single',
        'shrink',
        'case',
        'pallet',
        'single_price',
        'shrink_price',
        'case_price',
        'pallet_price',
        'remarks',
        'stoc_vat',
        'cost',
        'markup',
        'autoprice',
        'min_order_quantity',
        'stock_expiry_date',
        'audited',
        'promotion_id',
    ];

    /**
     * @var array
     */
    public $casts = [
        'stock_expiry_date' => 'date',
    ];

    // protected $dates = ['stock_expiry_date'];

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
    }
    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    public function supplier()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'uuid');
    }

    public function scopeOfSupplier($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOnlyActive($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->whereNull('deleted_at');
        });
    }

    public function scopeOfNoParent($query)
    {
        if (auth()->check()) {
            return $query->whereHas('product', function ($q) {
                $q->where('parent_id', '!=', '0');
            });

            // return $query->where('parent_id','!=','0');
        }
        return $query;
    }

    public function scopeProductNameFilter($query, $productName)
    {
        return $query->whereHas('product', function ($q) use ($productName) {
            $q->where('name', 'LIKE', "%$productName%");
        });
    }

    public function scopeProductBrandFilter($query, $brandName)
    {
        return $query->whereHas('product.brand', function ($q) use ($brandName) {
            $q->where('name', 'LIKE', "%$brandName%");
        });
    }

    public function scopeProductStockTypeFilter($query, $productStockType)
    {
        return $query->whereHas('product', function ($q) use ($productStockType) {
            $q->where('stock_type', 'LIKE', "%$productStockType%");
        });
    }

    public function getProductNameAttribute()
    {
        return $this->product()->exists() ? $this->product->name : null;
    }

    public function getProductBrandNameAttribute()
    {
        return $this->product()->exists() ? $this->product->brand_name : null;
    }

    public function getProductStockTypeAttribute()
    {
        return $this->product()->exists() ? $this->product->stock_type : null;
    }

    public function getProductBasePriceAttribute()
    {
        return $this->product()->exists() ? $this->product->base_price : null;
    }
}
