<?php

namespace App\Models;

use App\Models\History\BannerHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use BaseModelSupport;
    use SoftDeletes;

    protected $perPage = 20;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'banner';
    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'image',
        'video',
        'page_name',
        'sequence_number',
        'in_slider',
        'status'
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'slug' => 'string',
        'image' => 'string',
        'video' => 'string',
        'page_name' => 'string',
        'sequence_number' => 'string',
        'in_slider' => 'string',
        'status' => 'string',
    ];

    // protected $appends = [
    //     'grid_image',
    // ];

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
            $model->createUserLog($model, 'userActivity.banner|created');
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
                $model->createUserLog($model, 'userActivity.banner|updated');
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
        return $this->hasMany(BannerHistory::class, "history_of", 'uuid');
    }


    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

    public function getDropDown($except = null)
    {
        return $this->where('uuid', '!=', $except)->orderBy('name')->pluck('name', 'uuid');
    }


    public function getGridImageAttribute()
    {
        if(is_null($this->icon_file))
        {
            return NULL;
        }

        return "<a href='{$this->icon_file}' data-fancybox='gallery' title='{$this->name}' class='grid-thumb-image'><img src='{$this->icon_file}' style='max-width: 100px' /></a>";
    }
}
