<?php

namespace App\Models;

use App\Models\History\DeliveryVehicleMasterHistory;
use App\Models\History\ProductUnitHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\TransportType;

class DeliveryVehicleMaster extends Model
{
    use BaseModelSupport;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $perPage = 20;
    protected $vehicle_capacity_uuid = null;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'vehicle_type',
        'vehicle_capacity_id',
        'transport_type',
        'capacity',
        'price_per_km',
        'pallet_capacity_standard',
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
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'vehicle_type' => 'string',
        'vehicle_capacity_id' => 'string',
        'capacity' => 'float',
        'price_per_km' => 'float',
        'pallet_capacity_standard' => 'float'
    ];

    // public $transportTypes = [
    //     'Truck',
    //     'Bakki'
    // ];

    public function transportTypes()
    {
        $TransportType = TransportType::all();
        return $TransportType->pluck('type')->toArray();
    }


    /**
     * Boot Method
     * @return void
     */
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

        /**
         * @see BaseModelSupport::createUserActivity()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.deliveryVehicleMaster|created');
            $model->generateHistory($model);
        });

        static::updating(function ($model) {
            // $model->checkSlugUpdate($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserActivity()
         */
        static::updated(function ($model) {
            $model->generateHistory($model);
            $model->createUserLog($model, 'userActivity.deliveryVehicleMaster|updated');
        });

        static::deleting(function ($model) {
            $model->deleteHistory($model);
        });
    }

    /**
     * @return null
     */
    public function getVehicleCapacityUuid()
    {
        return $this->vehicle_capacity_id;
    }

    /**
     * @param null $vehicle_capacity_uuid
     */
    public function setVehicleCapacityUuid($vehicle_capacity_uuid): void
    {
        $this->vehicle_capacity_uuid = $vehicle_capacity_uuid;
    }

    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(DeliveryVehicleMasterHistory::class, "history_of", 'uuid');
    }

    public function scopeOfVehicleCapacity($query)
    {
        return $query->where('vehicle_capacity_id', $this->vehicle_capacity_uuid);
    }

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

    public function getDeliveryPrice($weightInKg, $distanceInKm)
    {
        $distanceInKm = str_replace(",", "", $distanceInKm);
        $distance = preg_split('/(?<=[0-9])(?=[^0-9]+)/i', $distanceInKm);
        $distanceNumber = $distance[0];
        $price = 0;
        $palletCapacity = 0;
        $vehicle = null;
        $weightInTon = $weightInKg / 1000;

        if ($this->where('capacity', '>=', $weightInTon)->count() > 0) {
            $vehicle = $this->where('capacity', '>=', $weightInTon)->orderBy('capacity', 'ASC')->first();
            $price = $vehicle->price_per_km * $distanceNumber;
            $palletCapacity = $vehicle->pallet_capacity_standard;
        }

        // dd($price.' '.$vehicle.' '.$palletCapacity);

        return ["price" => $price, "vehicle" => $vehicle, "palletCapacity" => $palletCapacity];
    }

    public function getCapacityDropDown()
    {
        return $this->pluck('capacity', 'uuid');
    }

    public function getOtherCapacityDropDown()
    {
        return $this->where('transport_type', '!=', 'Truck')->select('capacity', 'vehicle_type')->pluck('vehicle_type', 'capacity');
    }

    public function getOtherCapacitySelect($type)
    {
        return $this->where('transport_type', $type)->select('vehicle_type')->pluck('vehicle_type', 'vehicle_type');
    }
    public function getTransportTypesDropDown()
    {
        return array_combine($this->getTransportTypes(), $this->getTransportTypes());
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
}
