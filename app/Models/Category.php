<?php

namespace App\Models;

use App\Models\History\CategoryHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use BaseModelSupport;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $perPage = 20;

    public $categoryLevel = 5;
    protected $table = 'categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'parent_category_id',
        'banner_image_file',
        'thumb_image_file',
        'description',
        'short_description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'slug' => 'string',
        'parent_category_id' => 'string',
        'banner_image_file' => 'string',
        'thumb_image_file' => 'string',
        'description' => 'string',
        'short_description' => 'string',
        'meta_title' => 'string',
        'meta_description' => 'string',
        'meta_keywords' => 'string',
        'status' => 'string',
    ];

    protected $appends = [
        'grid_thumb_image',
        'parent_category_name',
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
            $model->checkSlug($model);
        });

        /**
         * @see BaseModelSupport::createUserActivity()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.category|created');
            $model->generateHistory($model);
        });

        static::updating(function ($model) {
            $model->checkSlugUpdate($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserActivity()
         */
        static::updated(function($model){
            if(!auth()->guest())
            {
                $model->generateHistory($model);
                $model->createUserLog($model, 'userActivity.category|updated');
            }
        });

        static::deleting(function ($model){
            $model->deleteHistory($model);
        });
    }

    /**
     * @return int
     */
    public function getCategoryLevel(): int
    {
        return $this->categoryLevel;
    }

    /**
     * @param int $categoryLevel
     */
    public function setCategoryLevel(int $categoryLevel): void
    {
        $this->categoryLevel = $categoryLevel;
    }



    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history() : HasMany
    {
        return $this->hasMany(CategoryHistory::class, "history_of", 'uuid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childCategory() : HasMany
    {
        return $this->hasMany(Category::class, "parent_category_id", 'uuid');
    }

    public function parentCategory() : BelongsTo
    {
        return $this->belongsTo(Category::class, "parent_category_id", 'uuid');
    }

    public function categoryProduct() : HasMany
    {
        return $this->hasMany(ProductCategory::class, 'category_id', 'uuid');
    }

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

    public function getDropDown($except = null)
    {
        return $this->where('uuid', '!=', $except)->orderBy('name')->pluck('name', 'uuid');
    }

    public static function getAllDropDown($except = null)
    {
        return Category::where('uuid', '!=', $except)->has("categoryProduct.product")->orderBy('name')->pluck('name', 'slug');
    }

    public function getParentCategoriesForCategory()
    {
        return $this->whereNull('parent_category_id')->orderBy('name')->pluck('name', 'uuid');
    }

    public function getParentCategories()
    {
        return $this->whereNull('parent_category_id')->orderBy('name')->get();
    }

    public function getParentCategoriesLimit()
    {
        return $this->whereNull('parent_category_id')->orderBy('name')->take(7)->get();
    }

    public function getSubCategories()
    {
        return $this->whereNotNull('parent_category_id')->orderBy('name')->get();
    }

    public function hasChild()
    {
        return $this->childCategory()->exists() ? true : false;
    }

    public function getParentCategoryNameAttribute()
    {
        return $this->parentCategory()->exists() ? $this->parentCategory->name : NULL;
    }
    public function getGridThumbImageAttribute()
    {
        if(is_null($this->thumb_image_file))
        {
            return NULL;
        }
        return "<a href='{$this->thumb_image_file}' data-fancybox='gallery' title='{$this->name}' class='grid-thumb-image'><img src='{$this->thumb_image_file}' style='max-width: 100px' /></a>";
    }

    public function getCategoryHierarchy($category, $selectedCategories)
    {
        return view('admin.helpers.common.childCategoryList', compact('category', 'selectedCategories'));
    }

    public function getGroupedCategories()
    {
        $categories = $this->whereNotNull('parent_category_id')->orderBy('name')->get();

        $grouped = $categories->groupBy(function($item, $key) {
            return $item->name[0];          // treats the name string as an array
        })
        ->sortBy(function($item, $key){      // sorts A-Z at the top level
            return $key;
        });

        return $grouped;
    }
}