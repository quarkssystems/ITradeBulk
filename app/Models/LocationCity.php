<?php

namespace App\Models;

use App\Models\History\LocationCityHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class LocationCity extends Model
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
        'city_name',
        'state_id',
        'country_id',
        'status',
        'latitude',
        'longitude',
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'city_name' => 'string',
        'status' => 'string',
        'state_id' => 'string',
        'country_id' => 'string',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public $country_uuid = null;
    public $state_uuid = null;

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
            $model->createUserLog($model, 'userActivity.locationCity|created');
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
                $model->createUserLog($model, 'userActivity.locationCity|updated');
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
        return $this->hasMany(LocationCityHistory::class, "history_of", 'uuid');
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
    public function state() : BelongsTo
    {
        return $this->belongsTo(LocationState::class, 'state_id', 'uuid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function zipcodes() : HasMany
    {
        return $this->hasMany(LocationZipcode::class, 'city_id', 'uuid');
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

    /**
     * @return null
     */
    public function getStateUuid()
    {
        return $this->state_uuid;
    }

    /**
     * @param null $state_uuid
     */
    public function setStateUuid($state_uuid): void
    {
        $this->state_uuid = $state_uuid;
    }

    /*==================================================*/
    /* Local Scopes */
    /*==================================================*/
    public function scopeOfCountry($query)
    {
        return $query->where('country_id', $this->country_uuid);
    }

    public function scopeOfState($query)
    {
        return $query->where('state_id', $this->state_uuid);
    }

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/
    public function getDropDown()
    {
        return $this->where('status','ACTIVE')->pluck('city_name', 'uuid');
    }

    public function getZipcodeDropDown()
    {
        return $this->zipcodes()->select(DB::raw("CONCAT(zipcode,'-',zipcode_name) AS zip"), 'uuid')->pluck('zip', 'uuid');
    }
}
