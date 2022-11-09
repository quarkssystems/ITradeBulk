<?php

namespace App\Models;

use App\Models\History\LocationZipcodeHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class LocationZipcode extends Model
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
        'zipcode_name',
        'zipcode',
        'city_id',
        'state_id',
        'country_id',
        'status'
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'zipcode_name' => 'string',
        'zipcode' => 'string',
        'status' => 'string',
        'city_id' => 'string',
        'state_id' => 'string',
        'country_id' => 'string',
    ];

    public $country_uuid = null;
    public $state_uuid = null;
    public $city_uuid = null;

    /**
     * Boot Method
     * @return void
     */
    protected static function boot() : void
    {
        parent::boot();
        /**
         * @see BaseModelSupport::addUUID()
         */
        static::creating(function ($model) {
            $model->addUUID($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserLog()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.locationZipcode|created');
            $model->generateHistory($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserLog()
         */
        static::updated(function($model){
            if(!auth()->guest())
            {
                $model->generateHistory($model);
                $model->createUserLog($model, 'userActivity.locationZipcode|updated');
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
        return $this->hasMany(LocationZipcodeHistory::class, "history_of", 'uuid');
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
    public function cities() : BelongsTo
    {
        return $this->belongsTo(LocationCity::class, 'city_id', 'uuid');
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

    /**
     * @return null
     */
    public function getCityUuid()
    {
        return $this->city_uuid;
    }

    /**
     * @param null $city_uuid
     */
    public function setCityUuid($city_uuid): void
    {
        $this->city_uuid = $city_uuid;
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

    public function scopeOfCity($query)
    {
        return $query->where('city_id', $this->city_uuid);
    }

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/
    public function getDropDown()
    {
        return $this->where('status','ACTIVE')->pluck('zipcode_name', 'uuid');
    }

    public function getDropDownWithZipCode()
    {
        return $this->select(DB::raw('CONCAT(zipcode_name," - ", zipcode) AS zipcode_name'), 'uuid')->pluck('zipcode_name', 'uuid');
    }
}
