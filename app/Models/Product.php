<?php

namespace App\Models;

use App\Models\History\ProductHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use DB;
use App\Scopes\ActiveScope;
use stdClass;

class Product extends Model
{
    use BaseModelSupport;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    // protected $dates = ['deleted_at', 'stock_expiry_date', 'period_from', 'period_to'];
    protected $perPage = 20;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'uuid',
    //     'name',
    //     'slug',
    //     'unit',
    //     'unit_name',
    //     'unit_value',
    //     'description',
    //     'short_description',
    //     'base_image',
    //     'base_price',
    //     'search_keyword',
    //     'meta_title',
    //     'meta_keyword',
    //     'meta_description',
    //     'min_price',
    //     'max_price',
    //     'brand_id',
    //     'tax_id',

    //     'single_qty',
    //     'single_weight',
    //     'shrink_qty',
    //     'shrink_weight',
    //     'case_qty',
    //     'case_weight',
    //     'pallet_qty',
    //     'pallet_weight',

    //     'single_height',
    //     'single_width',
    //     'arrival_type',
    //     'single_length',
    //     'shrink_height',
    //     'shrink_width',
    //     'shrink_length',
    //     'case_height',
    //     'case_width',
    //     'case_length',
    //     'pallet_height',
    //     'pallet_width',
    //     'pallet_length',

    //     'single_bundle_of',
    //     'shrink_bundle_of',
    //     'case_bundle_of',
    //     'pallet_bundle_of',

    //     'user_id',
    //     'barcode',
    //     'status',
    //     'stoc_wt',
    //     'stock_of',
    //     'stock_type',
    //     'stock_gst',

    //     'parent_id',
    //     'variant_id',
    //     'default_stock_type',

    //     //new fields added
    //     'has_upc',
    //     'product_code',
    //     'unit_barcode_link',
    //     'size',
    //     'size_description',
    //     'height',
    //     'width',
    //     'depth',
    //     'product_brand',
    //     'colour',
    //     'colour_variants',
    //     'size_variants',
    //     'department',
    //     'subdepartment',
    //     'category',
    //     'subcategory',
    //     'segment',
    //     'subsegment',
    //     'spec_sheet_url',
    //     'warranty'

    // ];

    protected $fillable = [
        'user_id',
        'store_id',
        'name',
        'slug',
        'unit',
        'unit_name',
        'unit_value',
        'search_keyword',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'stoc_wt',
        'stock_of',
        'stock_type',
        'stock_gst',
        'default_stock_type',
        'subdepartment',
        'spec_sheet_url',
        'audited',
        'published',
        'has_upc',
        'barcode',
        'product_code',
        'store_item_code',
        'parent_id',
        'variant_id',
        'unit_barcode_link',
        'description',
        'manufacturer',
        'category_group',
        'department',
        'category',
        'subcategory',
        'segment',
        'subsegment',
        'vat',
        'cost',
        'markup',
        'autoprice',
        'price',
        'base_price',
        'quantity',
        'min_order_quantity',
        'stock_expiry_date',
        'packing',
        'units_per_packing',
        'size',
        'unit_of_measure',
        'size_description',
        'height',
        'width',
        'depth',
        'weight',
        'colour',
        'colour_variants',
        'size_variants',
        'product_specification',
        'warranty',
        'attributes',
        'base_image',
        'alternate_image_1',
        'alternate_image_2',
        'promotion_type',
        'promotion_id',
        'period_from',
        'period_to',
        'promotion_price',
        'courier_safe',
        'out_of_stock_lead_time',
        'is_permanent_lead_product',
        'product_delivery_type',
        'arrival_type',
        'brand_id',
        'tax_id',
        'status',

    ];

    /**
     * @var array
     */
    // public $casts = [
    //     'uuid' => 'string',
    //     'name' => 'string',
    //     'slug' => 'string',
    //     'unit' => 'string',
    //     'unit_name' => 'string',
    //     'unit_value' => 'float',
    //     'weight' => 'float',
    //     'description' => 'string',
    //     'short_description' => 'string',
    //     'base_image' => 'string',
    //     'base_price' => 'float',
    //     'search_keyword' => 'string',
    //     'meta_title' => 'string',
    //     'meta_description' => 'string',
    //     'meta_keywords' => 'string',
    //     'min_price' => 'float',
    //     'max_price' => 'float',
    //     'brand_id' => 'string',
    //     'tax_id' => 'string',

    //     'single_qty' => 'float',
    //     'single_weight' => 'float',
    //     'shrink_qty' => 'float',
    //     'shrink_weight' => 'float',
    //     'case_qty' => 'float',
    //     'case_weight' => 'float',
    //     'pallet_qty' => 'float',
    //     'pallet_weight' => 'float',

    //     'single_height' => 'float',
    //     'single_width' => 'float',
    //     'arrival_type' => 'string',
    //     'single_length' => 'float',
    //     'shrink_height' => 'float',
    //     'shrink_width' => 'float',
    //     'shrink_length' => 'float',
    //     'case_height' => 'float',
    //     'case_width' => 'float',
    //     'case_length' => 'float',
    //     'pallet_height' => 'float',
    //     'pallet_width' => 'float',
    //     'pallet_length' => 'float',

    //     'single_bundle_of' => 'string',
    //     'shrink_bundle_of' => 'string',
    //     'case_bundle_of' => 'string',
    //     'pallet_bundle_of' => 'string',

    //     'user_id' => 'string',
    //     'barcode' => 'string',

    //     'status' => 'string',
    //     'stoc_wt' => 'float' ,
    //     'stock_of' => 'string',
    //     'stock_type' => 'string',
    //     'stock_gst' => 'integer',

    //     //new fields added
    //     'has_upc' => 'string',
    //     'product_code' => 'string',
    //     'unit_barcode_link' => 'string',
    //     'size' => 'float',
    //     'size_description' => 'string',
    //     'height' => 'float',
    //     'width' => 'float',
    //     'depth' => 'float',
    //     'product_brand' => 'string',
    //     'colour' => 'string',
    //     'colour_variants' => 'string',
    //     'size_variants' => 'string',
    //     'department' => 'string',
    //     'subdepartment' => 'string',
    //     'category' => 'string',
    //     'subcategory' => 'string',
    //     'segment' => 'string',
    //     'subsegment' => 'string',
    //     'spec_sheet_url' => 'string',
    //     'warranty' => 'string'
    // ];



    protected $appends = [
        'base_image_data',
        'brand_name',
        'first_name',
        'supplier_stock',
    ];

    public $supplierId = null;
    /**
     * Boot Method
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new ActiveScope);

        /**
         * @see BaseModelSupport::addUUID()
         * @see User::setUserType()
         * @see User::setClientId()
         */
        static::creating(function ($model) {
            $model->addUUID($model);
            $model->checkSlug($model);
            // $model->user_id = auth()->user()->uuid;
        });

        /**
         * @see BaseModelSupport::createUserActivity()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.product|created');
            // $model->generateHistory($model); //commented
        });

        static::updating(function ($model) {
            $model->checkSlugUpdate($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserActivity()
         */
        static::updated(function ($model) {
            if (!auth()->guest()) {
                // $model->generateHistory($model);
                $model->createUserLog($model, 'userActivity.product|updated');
            }
        });

        static::deleting(function ($model) {
            $model->deleteHistory($model);
        });
    }

    /**
     * @return null
     */
    public function getSupplierId()
    {
        return $this->supplierId;
    }

    /**
     * @param null $supplierId
     */
    public function setSupplierId($supplierId): void
    {
        $this->supplierId = $supplierId;
    }

    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(ProductHistory::class, "history_of", 'uuid');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, "brand_id", 'uuid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", 'uuid');
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, "tax_id", 'uuid');
    }

    public function productCategory(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'product_id', 'uuid');
    }

    public function supplierStock()
    {
        return $this->hasMany(SupplierItemInventory::class, 'product_id', 'uuid');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class, 'promotion_id', 'uuid');
    }

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

    public function scopeOfSupplier($query)
    {
        if (!is_null($this->getSupplierId())) {
            return $query->whereHas(['supplierStock' => function ($q) {
                $q->where('user_id', $this->getSupplierId());
            }]);
        }
        return $query;
    }

    public function scopeOfUser($query)
    {
        if (auth()->check()) {
            return $query->where("user_id", auth()->user()->uuid)->where('parent_id', '0');
        }
        return $query;
    }

    public function scopeOfUserNotAdmin($query)
    {
        if (auth()->check()) {
            return $query->where("user_id", '!=', auth()->user()->uuid);
        }
        return $query;
    }

    public function scopeOfNoChild($query)
    {
        if (auth()->check()) {
            return $query->where('parent_id', '0');
        }
        return $query;
    }

    public function scopeOfNoChildNew($query)
    {
        if (auth()->check()) {
            return $query->where('parent_id', null)->orwhere('parent_id', '0');
        }
        return $query;
    }

    public function scopeOfAuditedPublished($query)
    {
        if (auth()->check()) {
            return $query->whereIn('audited', ['0', '1'])->whereIn('published', ['0', '1'])->where('parent_id', '!=', '0');
        }
        return $query;
    }

    public function scopeOfNoParent($query)
    {
        if (auth()->check()) {
            return $query->where('parent_id', '!=', '0');
        }
        return $query;
    }

    public function scopeActive($query)
    {
        if (auth()->check()) {
            return $query->where("status", "ACTIVE");
        }
        return $query;
    }

    public function getDropDown($except = null)
    {
        return $this->where('uuid', '!=', $except)->orderBy('name')->pluck('name', 'uuid');
    }

    public function getBaseImageDataAttribute()
    {
        if (is_null($this->base_image)) {
            return NULL;
        }

        return "<a href='{$this->base_image}' data-fancybox='gallery' title='{$this->name}' class='grid-thumb-image'><img src='{$this->base_image}' style='max-width: 100px' /></a>";
    }


    public function getFirstNameAttribute()
    {
        return $this->user()->exists() ? $this->user->name : "";
    }

    public function getBrandNameAttribute()
    {
        return $this->brand()->exists() ? $this->brand->name : null;
    }

    public function getUserNameAttribute()
    {
        return $this->user()->exists() ? $this->user->name : "";
    }

    public function getTaxNameAttribute()
    {
        return $this->tax()->exists() ? $this->tax->name : null;
    }
    public function getTaxValueAttribute()
    {
        return $this->tax()->exists() ? $this->tax->value : null;
    }

    public function getIsPromotionAttribute($product)
    {
        $promotion = Promotion::whereDate('promotions.period_from', '<=', date("Y-m-d"))
            ->whereDate('promotions.period_to', '>=', date("Y-m-d"))
            ->where('product_id', $this->uuid)->get();

        if (count($promotion) != 0) {
            $isOnPromotion = '1';
        } else {
            $isOnPromotion = '0';
        }
        return $isOnPromotion;
    }

    public function getPdetailsAttribute()
    {
        $promotion = Promotion::whereDate('promotions.period_from', '<=', date("Y-m-d"))
            ->whereDate('promotions.period_to', '>=', date("Y-m-d"))
            ->where('product_id', $this->uuid)->first();
        return $promotion;
        // if ($promotion != null) {

        //     dd('hi', $promotion, $this->uuid, date("Y-m-d"));
        // }

        // $isOnPromotion = new stdClass;
        // if ($promotion != null) {
        //     $isOnPromotion->period_from = $promotion->period_from;
        //     $isOnPromotion->period_to = $promotion->period_to;
        // } else {
        //     $isOnPromotion->from_date = '';
        //     $isOnPromotion->to_date = '';
        // }
        // return $isOnPromotion;
    }

    public function getStockProductAttribute()
    {

        return $productStock = [
            'single' => 'Single',
            'shrink' => 'Shrink',
            'case' => 'Case',
            'pallet' => 'Pallet',
        ];
    }

    public function getStockGST()
    {

        return $gst = [
            '1' => 'Yes',
            '0' => 'No'
        ];
    }

    public function getDefaultStockType()
    {

        return $defaultStockType = [
            '0' => 'No',
            '1' => 'Yes'
        ];
    }

    public function getColorVariants()
    {

        return $getColorVariants = [
            '0' => 'Red',
            '1' => 'Green',
            '2' => 'Blue',
        ];
    }

    public function getSizeVariants()
    {

        return $getSizeVariants = [
            '0' => 'Large',
            '1' => 'Medium',
            '2' => 'Small',
        ];
    }



    public function getSupplierStockAttribute()
    {
        //        TODO : Pass Supplier id here
        //DB::enableQueryLog(); // Enable query log
        $supplierId = $this->getSupplierId();
        $stockData = $this->supplierStock()->where('user_id', auth()->user()->uuid)->get();

        $stockExpiryData = $this->supplierStock()->where('product_id', $this->uuid)->get();


        //dd(DB::getQueryLog()); // Show results of log
        $productStock = [
            'single' => 0,
            'shrink' => 0,
            'case' => 0,
            'pallet' => 0,
        ];

        /* $pallet_bundle_of = $this->pallet_bundle_of;
        $pallet_qty = $this->pallet_qty;
        $palletStockData = $stockData->sum('pallet');
        if(isset($productStock[$pallet_bundle_of]))
        {
            $productStock[$pallet_bundle_of] = $productStock[$pallet_bundle_of] + ($pallet_qty * $palletStockData);
        }

        $case_bundle_of = $this->case_bundle_of;
        $case_qty = $this->case_qty;
        $caseStockData = $stockData->sum('case');
        if(isset($productStock[$case_bundle_of]))
        {
            $productStock[$case_bundle_of] = $productStock[$case_bundle_of] + ($case_qty * $caseStockData);
        }


        $shrink_bundle_of = $this->shrink_bundle_of;
        $shrink_qty = $this->shrink_qty;
        $shrinkStockData = $stockData->sum('shrink');
        if(isset($productStock[$shrink_bundle_of]))
        {
            $productStock[$shrink_bundle_of] = $productStock[$shrink_bundle_of] + ($shrink_qty * $shrinkStockData);
        } */

        $single_bundle_of = $this->single_bundle_of;
        $single_qty = $this->single_qty;
        $singleStockData = $stockData->sum('single');
        if (isset($productStock[$single_bundle_of])) {
            //            $productStock[$single_bundle_of] = $productStock[$single_bundle_of] + ($single_qty * $singleStockData);
        }

        return [
            'single_total' => $singleStockData + $productStock['single'],
            /* 'shrink_total' => $shrinkStockData + $productStock['shrink'],
            'case_total' => $caseStockData + $productStock['case'],
            'pallet_total' => $palletStockData + $productStock['pallet'], */
            'single' => $singleStockData,
            /* 'shrink' => $shrinkStockData,
            'case' => $caseStockData,
            'pallet' => $palletStockData + $productStock['pallet'], */
            'single_price' => !is_null($stockData->last()) ? $stockData->last()->single_price : 'NA',
            /* 'shrink_price' => !is_null($stockData->last()) ? $stockData->last()->shrink_price : 'NA',
            'case_price' => !is_null($stockData->last()) ? $stockData->last()->case_price : 'NA',
            'pallet_price' => !is_null($stockData->last()) ? $stockData->last()->pallet_price : 'NA', */
            'stock_expiry_date' =>  !is_null($stockExpiryData->last()) && ($stockExpiryData->last()->stock_expiry_date != null) ?  \Carbon\Carbon::parse($stockExpiryData->last()->stock_expiry_date)->format('d/m/Y') : 'NA',
        ];
    }

    public function getAdminSupplierStockAttribute()
    {
        //        TODO : Pass Supplier id here
        $supplierId = $this->getSupplierId();
        $stockData = $this->supplierStock()->where('user_id', $supplierId)->get();

        $productStock = [
            'single' => 0,
            'shrink' => 0,
            'case' => 0,
            'pallet' => 0,
        ];

        $pallet_bundle_of = $this->pallet_bundle_of;
        $pallet_qty = $this->pallet_qty;
        $palletStockData = $stockData->sum('pallet');
        if (isset($productStock[$pallet_bundle_of])) {
            $productStock[$pallet_bundle_of] = $productStock[$pallet_bundle_of] + ($pallet_qty * $palletStockData);
        }

        $case_bundle_of = $this->case_bundle_of;
        $case_qty = $this->case_qty;
        $caseStockData = $stockData->sum('case');
        if (isset($productStock[$case_bundle_of])) {
            $productStock[$case_bundle_of] = $productStock[$case_bundle_of] + ($case_qty * $caseStockData);
        }


        $shrink_bundle_of = $this->shrink_bundle_of;
        $shrink_qty = $this->shrink_qty;
        $shrinkStockData = $stockData->sum('shrink');
        if (isset($productStock[$shrink_bundle_of])) {
            $productStock[$shrink_bundle_of] = $productStock[$shrink_bundle_of] + ($shrink_qty * $shrinkStockData);
        }

        $single_bundle_of = $this->single_bundle_of;
        $single_qty = $this->single_qty;
        $singleStockData = $stockData->sum('single');
        if (isset($productStock[$single_bundle_of])) {
            //            $productStock[$single_bundle_of] = $productStock[$single_bundle_of] + ($single_qty * $singleStockData);
        }

        return [
            'single_total' => $singleStockData + $productStock['single'],
            'shrink_total' => $shrinkStockData + $productStock['shrink'],
            'case_total' => $caseStockData + $productStock['case'],
            'pallet_total' => $palletStockData + $productStock['pallet'],
            'single' => $singleStockData,
            'shrink' => $shrinkStockData,
            'case' => $caseStockData,
            'pallet' => $palletStockData + $productStock['pallet'],
            'single_price' => !is_null($stockData->last()) ? $stockData->last()->single_price : 'NA',
            'shrink_price' => !is_null($stockData->last()) ? $stockData->last()->shrink_price : 'NA',
            'case_price' => !is_null($stockData->last()) ? $stockData->last()->case_price : 'NA',
            'pallet_price' => !is_null($stockData->last()) ? $stockData->last()->pallet_price : 'NA',
        ];
    }

    public function getCalculatedWeight($qtyType, $qty)
    {
        $weight = $this->stoc_wt;
        // dd($weight.' '.$qtyType.' '.$qty);
        switch ($qtyType) {
            case "single":
                $weight = $this->stoc_wt;
                break;
            case "shrink":
                $weight = $this->shrink_weight;
                break;
            case "case":
                $weight = $this->case_weight;
                break;
            case "pallet":
                $weight = $this->pallet_weight;
                break;
            default:
                $weight = 0;
                break;
        }

        return $weight * $qty;
    }
    public function getCalculatedTax($qtyType, $qty, $productPrice)
    {
        $itemTaxPrice = 0;
        $stock_gst = $this->stock_gst;
        if (!empty($stock_gst) && $stock_gst != 0) {
            $taxPercentage = $this->getTaxValueAttribute();
            if ($taxPercentage > 0) {
                switch ($qtyType) {
                    case "single":
                        $productPrice = $qty * $productPrice;
                        $itemTaxPrice = $productPrice * $taxPercentage / 100;
                        break;
                    default:
                        $itemTaxPrice = 0;
                        break;
                }
            }
        }

        return $itemTaxPrice;
    }


    public function getProductData()
    {
        return $this->select(
            DB::raw("CONCAT(name,' (',stock_type,')') AS name"),
            'uuid'
        )->where('parent_id', '!=', '0')->pluck('name', 'uuid');
    }
}
