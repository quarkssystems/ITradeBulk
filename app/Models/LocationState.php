<?php

namespace App\Models;

use App\Models\History\LocationStateHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationState extends Model
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
        'state_name',
        'country_id',
        'status'
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'state_name' => 'string',
        'status' => 'string',
        'country_id' => 'string',
    ];

    public $country_uuid = null;

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
            $model->createUserLog($model, 'userActivity.locationState|created');
            $model->generateHistory($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserActivity()
         */
        static::updated(function($model){
            if(!auth()->guest())
            {
                $model->generateHistory($model);
                $model->createUserLog($model, 'userActivity.locationState|updated');
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
        return $this->hasMany(LocationStateHistory::class, "history_of", 'uuid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country() : BelongsTo
    {
        return $this->belongsTo(LocationCountry::class, 'country_id', 'uuid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities() : HasMany
    {
        return $this->hasMany(LocationCity::class, 'state_id', 'uuid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function zipcodes() : HasMany
    {
        return $this->hasMany(LocationZipcode::class, 'state_id', 'uuid');
    }

    /*==================================================*/
    /* Getters and Setters */
    /*==================================================*/

    /**
     * @return null
     */
    public function getCountryUuid()
    {
        return $this->country_uuid;
    }

    /**
     * @param null $country_uuid
     */
    public function setCountryUuid($country_uuid): void
    {
        $this->country_uuid = $country_uuid;
    }

    /*==================================================*/
    /* Local Scopes */
    /*==================================================*/
    public function scopeOfCountry($query)
    {
        return $query->where('country_id', $this->country_uuid);
    }

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/
    public function getDropDown()
    {
        return $this->where('status','ACTIVE')->pluck('state_name', 'uuid');
    }

    public function getCityDropDown()
    {
        return $this->cities()->pluck('city_name', 'uuid');
    }
}
