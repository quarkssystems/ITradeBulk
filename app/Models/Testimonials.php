<?php

namespace App\Models;
use App\Models\History\TestimonialsHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonials extends Model
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
    protected $table = 'testimonials';
    protected $fillable = [
        'uuid',
        'type',
        'client_id',
        'slug',
        'message',
        'status'
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'type' => 'string',
        'client_id' => 'string',
        'slug' => 'string',
        'message' => 'string',
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
            $model->createUserLog($model, 'userActivity.testimonials|created');
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
                $model->createUserLog($model, 'userActivity.testimonials|updated');
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
        return $this->hasMany(TestimonialsHistory::class, "history_of", 'id');
    }


    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

    public function getDropDown($except = null)
    {
        return $this->where('id', '!=', $except)->orderBy('name')->pluck('name', 'id');
    }


    public function getGridImageAttribute()
    {
        if(is_null($this->icon_file))
        {
            return NULL;
        }

        return "<a href='{$this->icon_file}' data-fancybox='gallery' title='{$this->name}' class='grid-thumb-image'><img src='{$this->icon_file}' style='max-width: 100px' /></a>";
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'client_id');
    }
}
