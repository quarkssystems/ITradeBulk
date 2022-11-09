<?php

namespace App\Models;

use App\Models\History\BankBranchHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankBranch extends Model
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
        'branch_name',
        'branch_code',
        'swift_code',
        'bank_master_id',
        'address1',
        'address2',
        'zipcode_id',
        'city_id',
        'state_id',
        'country_id',
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'branch_name' => 'string',
        'branch_code' => 'string',
        'swift_code' => 'array',
        'bank_master_id' => 'string',
        'address1' => 'string',
        'address2' => 'string',
        'zipcode_id' => 'string',
        'city_id' => 'string',
        'state_id' => 'string',
        'country_id' => 'string',
    ];

    public $appends = [
        'bank_name',
        'state_name',
        'city_name',
        'zipcode_name',
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
        });

        /**
         * @see BaseModelSupport::createUserActivity()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.bankBranch|created');
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
                $model->createUserLog($model, 'userActivity.bankBranch|updated');
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
        return $this->hasMany(BankBranchHistory::class, "history_of", 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function bank() : BelongsTo
    {
        return $this->belongsTo(BankMaster::class, "bank_master_id", 'uuid');
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
    /* Local Scopes */
    /*==================================================*/

    public function scopeOfBank($query, $bank)
    {
        return $query->where('bank_master_id', $bank);
    }

    public function scopeOfZipcode($query, $zip)
    {
        return $query->whereHas('zipcode', function($q) use ($zip){
            $q->where('zipcode_name', 'LIKE', "%$zip%")->orWhere('zipcode', $zip);
        });
    }

    public function scopeOfCity($query, $city)
    {
        return $query->whereHas('city', function($q) use ($city){
            $q->where('city_name', 'LIKE', "%$city%");
        });
    }

    public function scopeOfState($query, $state)
    {
        return $query->whereHas('state', function($q) use ($state){
            $q->where('state_name', 'LIKE', "%$state%");
        });
    }
    /*==================================================*/
    /* Accessor and Mutators */
    /*==================================================*/

    public function getBankNameAttribute()
    {
        return $this->bank->name;
    }
    public function getStateNameAttribute()
    {
        return $this->state()->exists() ? $this->state->state_name : "";
    }
    public function getCityNameAttribute()
    {
        return $this->city->city_name;
    }
    public function getZipcodeNameAttribute()
    {
        return "{$this->zipcode->zipcode_name} - {$this->zipcode->zipcode}";
    }

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/
}
