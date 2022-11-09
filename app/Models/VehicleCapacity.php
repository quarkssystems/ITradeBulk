<?php

namespace App\Models;

use App\Models\History\TeamHistory;
use App\Models\History\VehicleCapacityHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleCapacity extends Model
{
    use BaseModelSupport;
    use SoftDeletes;

    protected $perPage = 20;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'name',
        'max_weight',
        'load_space_volume',
        'load_floor_length',
        'load_floor_width',
        'side_load_height',
        'side_load_length',
        'pallet_capacity_standard',
        'pallet_capacity_euro',
        'full_pallet_dimension_width',
        'full_pallet_dimension_depth',
        'full_pallet_dimension_height',
        'full_pallet_dimension_max_weight',
        'half_pallet_dimension_width',
        'half_pallet_dimension_depth',
        'half_pallet_dimension_height',
        'half_pallet_dimension_max_weight',
        'quarter_pallet_dimension_width',
        'quarter_pallet_dimension_depth',
        'quarter_pallet_dimension_height',
        'quarter_pallet_dimension_max_weight'
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'max_weight' => 'float',
        'load_space_volume' => 'float',
        'load_floor_length' => 'float',
        'load_floor_width' => 'float',
        'side_load_height' => 'float',
        'side_load_length' => 'float',
        'pallet_capacity_standard' => 'float',
        'pallet_capacity_euro' => 'float',
        'full_pallet_dimension_width' => 'float',
        'full_pallet_dimension_depth' => 'float',
        'full_pallet_dimension_height' => 'float',
        'full_pallet_dimension_max_weight' => 'float',
        'half_pallet_dimension_width' => 'float',
        'half_pallet_dimension_depth' => 'float',
        'half_pallet_dimension_height' => 'float',
        'half_pallet_dimension_max_weight' => 'float',
        'quarter_pallet_dimension_width' => 'float',
        'quarter_pallet_dimension_depth' => 'float',
        'quarter_pallet_dimension_height' => 'float',
        'quarter_pallet_dimension_max_weight' => 'float'
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
            // $model->checkSlug($model);
        });

        /**
         * @see BaseModelSupport::createUserActivity()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.vehicleCapacity|created');
            $model->generateHistory($model);
        });

        static::updating(function ($model) {
            // $model->checkSlugUpdate($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserActivity()
         */
        static::updated(function($model){
            if(!auth()->guest())
            {
                $model->generateHistory($model);
                $model->createUserLog($model, 'userActivity.vehicleCapacity|updated');
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
        return $this->hasMany(VehicleCapacityHistory::class, "history_of", 'uuid');
    }


    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

    public function getDropDown()
    {
        return $this->pluck('name', 'uuid');
    }

}