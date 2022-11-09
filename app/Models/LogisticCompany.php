<?php

namespace App\Models;

use App\Models\History\LogisticCompanysHistory;
use App\Models\Support\BaseModelSupport;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogisticCompany extends Model
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
        'title',
        'first_name',
        'last_name',
        'gender',
        'email',
        'mobile',
        'transporter_name',
        'logistic_type',
        'status',
        'latitude',
        'longitude',
        'remarks',
        'image',
        'phone',
        'driving_licence',
        'transport_type',
        'transport_capacity',
        'pallets_available',
        'pallets_required',
        'pallets_deposit',
        'work_type',
        'availability',
        'address1',
        'address2',
        'zipcode_id',
        'city_id',
        'state_id',
        'country_id',
        'vehicle_capacity_id'
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'title' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'gender' => 'string',
        'email' => 'string',
        'mobile' => 'string',
        'transporter_name' => 'string',
        'logistic_type' => 'string',
        'status' => 'string',
        'latitude' => 'string',
        'longitude' => 'string',
        'remarks' => 'string',
        'image' => 'string',
        'phone' => 'string',
        'driving_licence' => 'string',
        'transport_type' => 'string',
        'transport_capacity' => 'string',
        'pallets_available' => 'string',
        'pallets_required' => 'string',
        'pallets_deposit' => 'string',
        'work_type' => 'string',
        'availability' => 'string',
        'address1' => 'string',
        'address2' => 'string',
        'zipcode_id' => 'string',
        'city_id' => 'string',
        'state_id' => 'string',
        'country_id' => 'string',
        'vehicle_capacity_id' => 'string'
    ];

    public $userId = null;

    public $transportTypes = [
        'Truck',
        'Bakki',
        'Van',
        'Other'
    ];

    public $workType = [
        'FULL TIME',
        'PART TIME',
        'OTHER'
    ];

    public $availabilityTypes = [
        'ANYTIME',
        'PICK HOURS',
        'OFF PICK HOURS',
        'WEEKDAYS ONLY',
        'WEEKENDS ONLY',

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
         */
        static::creating(function ($model) {
            $model->addUUID($model);
        });

        /**
         * @see BaseModelSupport::createUserLog()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.logisticDetails|created');
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
                $model->createUserLog($model, 'userActivity.logisticDetails|updated');
            }
        });

        static::deleting(function ($model){
            $model->deleteHistory($model);
        });
    }

    public function transportTypes(){
        $TransportType = TransportType::all();
        return $TransportType->pluck('type')->toArray();
    }
    /*==================================================*/
    /* Getter and Setter */
    /*==================================================*/

    /**
     * @return array
     */
    public function getTransportTypes(): array
    {
        return $this->transportTypes();
        // return $this->transportTypes;
    }

    /**
     * @param array $transportTypes
     */
    public function setTransportTypes(array $transportTypes): void
    {
        $this->transportTypes = $transportTypes;
    }

    /**
     * @return array
     */
    public function getWorkType(): array
    {
        return $this->workType;
    }

    /**
     * @param array $workType
     */
    public function setWorkType(array $workType): void
    {
        $this->workType = $workType;
    }

    /**
     * @return array
     */
    public function getAvailabilityTypes(): array
    {
        return $this->availabilityTypes;
    }

    /**
     * @param array $availabilityTypes
     */
    public function setAvailabilityTypes(array $availabilityTypes): void
    {
        $this->availabilityTypes = $availabilityTypes;
    }


    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history() : HasMany
    {
        return $this->hasMany(LogisticCompanysHistory::class, "history_of", 'uuid');
    }

    /**
     * @return HasOne
     */
    public function taxDetails() : HasOne
    {
        return $this->hasOne(LogisticCompanyTaxDetails::class, 'logistic_company_id', 'uuid');
    }


    /**
     * @return HasOne
     */
    public function bankDetails() : HasOne
    {
        return $this->hasOne(LogisticCompanyBankDetails::class, 'logistic_company_id', 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function zipcode() : BelongsTo
    {
        return $this->belongsTo(LocationZipcode::class, "zipcode_id", 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function city() : BelongsTo
    {
        return $this->belongsTo(LocationCity::class, "city_id", 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function state() : BelongsTo
    {
        return $this->belongsTo(LocationState::class, "state_id", 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function country() : BelongsTo
    {
        return $this->belongsTo(LocationCountry::class, "country_id", 'uuid');
    }

    /*==================================================*/
    /* Accessor and Mutators */
    /*==================================================*/

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

    public function getTransportTypesDropDown()
    {
        return array_combine($this->getTransportTypes(), $this->getTransportTypes());
    }

    public function getWorkTypesDropDown()
    {
        return array_combine($this->getWorkType(), $this->getWorkType());
    }

    public function getAvailabilityTypesDropDown()
    {
        return array_combine($this->getAvailabilityTypes(), $this->getAvailabilityTypes());
    }

    
}