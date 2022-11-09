<?php



namespace App\Models;



use App\Models\History\LogisticDetailsHistory;

use App\Models\Support\BaseModelSupport;

use App\User;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\TransportType;


class LogisticDetails extends Model

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

        'phone',

        'driving_licence',

        'transport_type',

        'vehicle_type',

        'transport_capacity',

        'pallet_capacity_standard',

        'pallets_available',

        'pallets_required',

        'pallets_deposit',

        'work_type',

        'availability',

        'user_id',

        'address1',

        'address2',

        'zipcode_id',

        'city_id',

        'state_id',

        'country_id',

        'vehicle_capacity_id',

        'vehicle_color',

        'vin_number',

        'vehicle_model',

        'vehicle_registration_number',

        'vehicle_make',
        'truck_length',
        'truck_width',
        'truck_height',
        'truck_payload',
        'truck_max_pallets',
        'trailer_length',
        'trailer_width',
        'trailer_height',
        'trailer_payload',
        'trailer_max_pallets',
        'body_volumn',
        'combine_payload',
        'combine_pallets',
        'trading_area',
        'name'
    ];



    /**

     * @var array

     */

    public $casts = [

        'uuid' => 'string',

        'phone' => 'string',

        'driving_licence' => 'string',

        'transport_type' => 'string',

        'vehicle_type' => 'string',

        'transport_capacity' => 'string',

        'pallet_capacity_standard' => 'float',

        'pallets_available' => 'string',

        'pallets_required' => 'string',

        'pallets_deposit' => 'string',

        'work_type' => 'string',

        'availability' => 'string',

        'user_id' => 'string',

        'address1' => 'string',

        'address2' => 'string',

        'zipcode_id' => 'string',

        'city_id' => 'string',

        'state_id' => 'string',

        'country_id' => 'string',

        'vehicle_capacity_id' => 'string',

        'vehicle_color' => 'string',

        'vin_number' => 'string',

        'vehicle_model' => 'string',

        'vehicle_registration_number' => 'string',

        'vehicle_make' => 'string',

        'truck_length' => 'string',
        'truck_width' => 'string',
        'truck_height' => 'string',
        'truck_payload' => 'string',
        'truck_max_pallets' => 'string',
        'trailer_length' => 'string',
        'trailer_width' => 'string',
        'trailer_height' => 'string',
        'trailer_payload' => 'string',
        'trailer_max_pallets' => 'string',
        'body_volumn' => 'string',
        'combine_payload' => 'string',
        'combine_pallets' => 'string',
        'trading_area' => 'string'
    ];



    public $userId = null;


    // public $transportTypes = $TransportType->pluck('type');

    // public $transportTypes = [

    //     'Truck',

    //     'Bakki',

    //     'Truck with trailer'


    // ];

    public function transportTypes()
    {
        $TransportType = TransportType::all();
        return $TransportType->pluck('type')->toArray();
    }



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



    public $appends = [

        'country_name',

        'state_name',

        'city_name',

        'zipcode_name',

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

         */

        static::creating(function ($model) {

            $model->addUUID($model);
        });



        /**

         * @see BaseModelSupport::createUserLog()

         */

        static::created(function ($model) {

            $model->createUserLog($model, 'userActivity.logisticDetails|created');

            // $model->generateHistory($model);

        });



        /**

         * @see BaseModelSupport::generateHistory()

         * @see BaseModelSupport::createUserLog()

         */

        static::updated(function ($model) {

            if (!auth()->guest()) {

                $model->generateHistory($model);

                $model->createUserLog($model, 'userActivity.logisticDetails|updated');
            }
        });



        static::deleting(function ($model) {

            $model->deleteHistory($model);
        });
    }





    /*==================================================*/

    /* Getter and Setter */

    /*==================================================*/



    /**

     * @return null

     */

    public function getUserId()

    {

        return $this->userId;
    }



    /**

     * @param null $userId

     */

    public function setUserId($userId): void

    {

        $this->userId = $userId;
    }



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

    public function history(): HasMany

    {

        return $this->hasMany(LogisticDetailsHistory::class, "history_of", 'uuid');
    }



    /**

     * @return BelongsTo

     */

    public function owner(): BelongsTo

    {

        return $this->belongsTo(User::class, "user_id", 'uuid');
    }



    /**

     * @return BelongsTo

     */

    public function zipcode(): BelongsTo

    {

        return $this->belongsTo(LocationZipcode::class, "zipcode_id", 'uuid');
    }



    /**

     * @return BelongsTo

     */

    public function city(): BelongsTo

    {

        return $this->belongsTo(LocationCity::class, "city_id", 'uuid');
    }



    /**

     * @return BelongsTo

     */

    public function state(): BelongsTo

    {

        return $this->belongsTo(LocationState::class, "state_id", 'uuid');
    }



    /**

     * @return BelongsTo

     */

    public function country(): BelongsTo

    {

        return $this->belongsTo(LocationCountry::class, "country_id", 'uuid');
    }



    /*==================================================*/

    /* Local Scopes */

    /*==================================================*/



    public function scopeOfUser($query)

    {

        return $query->where('user_id', $this->userId);
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



    public function getAddressAttribute()

    {

        $address = [];

        if (!empty($this->address1)) {

            $address[] = $this->address1;
        }



        if (!empty($this->address2)) {

            $address[] = $this->address2;
        }



        if ($this->city()->exists()) {

            $address[] = $this->city->city_name;
        }



        if ($this->state()->exists()) {

            $address[] = $this->state->state_name;
        }



        if ($this->country()->exists()) {

            $address[] = $this->country->country_name;
        }



        if ($this->zipcode()->exists()) {

            $address[] = $this->zipcode->zipcode_name;

            $address[] = $this->zipcode->zipcode;
        }

        return $address;
    }





    /*==================================================*/

    /* Custom Methods */

    /*==================================================*/









    public function getVehicle($weightInKg) //get vehicle weoth

    {

        $weightInTon = $weightInKg / 1000;

        $weightInTon = ceil($weightInTon);



        $vehicle = NULL;

        if ($this->where('transport_capacity', '>=', $weightInTon)->where('is_available', '=', '1')->count() > 0) {



            $vehicleCpacity = $this->where('transport_capacity', '>=', $weightInTon)->select('transport_capacity')->orderBy('transport_capacity', 'ASC')->first();



            $vehicle = $this->where('transport_capacity', '=', $vehicleCpacity->transport_capacity)->select('uuid', 'user_id', 'vehicle_type', 'trading_area', 'pallet_capacity_standard', 'body_volumn')->orderBy('transport_capacity', 'ASC')->get();
        }

        // dd($vehicle);

        return $vehicle;
    }


    public function getVehicleData($weightInKg) //get vehicle weoth

    {

        $weightInTon = $weightInKg / 1000;
        $weightInTon = ceil($weightInTon);
        $vehicle = NULL;


        // $this->where('user_id', auth()->user()->uuid);
        if ($this->where('transport_capacity', '>=', $weightInTon)->count() > 0) {
            $vehicleCpacity = $this->where('transport_capacity', '>=', $weightInTon)
                ->select('transport_capacity')->orderBy('transport_capacity', 'ASC')->first();
            $vehicle = $this->where('transport_capacity', '=', $vehicleCpacity->transport_capacity)
                ->where('user_id', auth()->user()->uuid)
                ->where('status', '1')
                ->where('is_available', '1')
                ->select(
                    '*'
                    // 'uuid',
                    // 'user_id',
                    // 'vehicle_type',
                    // 'trading_area',
                    // 'pallet_capacity_standard',
                    // 'body_volumn'
                )->orderBy('transport_capacity', 'ASC')->first();

            if ($vehicle != null) {
                $vehicleData = VehicleCapacity::where('uuid', $vehicle->vehicle_capacity_id)->select('name')->first();
                if ($vehicleData != null) {
                    $vehicle->vehicle_type = $vehicleData->name;
                } else {
                    $vehicle->vehicle_type = '';
                }
            }
        }
        // dd($vehicle, $vehicleCpacity);
        return $vehicle;
    }


    public function getCountryNameAttribute()

    {

        return $this->country()->exists() ? $this->country->country_name : "";
    }

    public function getStateNameAttribute()

    {

        return $this->state()->exists() ? $this->state->state_name : "";
    }

    public function getCityNameAttribute()

    {

        return (isset($this->city) && $this->city != null) ? $this->city->city_name : '';
    }

    public function getZipcodeNameAttribute()

    {

        if (isset($this->zipcode) && $this->zipcode != null) {

            return "{$this->zipcode->zipcode_name} - {$this->zipcode->zipcode}";
        } else {
            return '';
        }
    }
}
