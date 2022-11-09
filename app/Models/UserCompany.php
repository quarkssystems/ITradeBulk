<?php

namespace App\Models;

use App\Models\History\UserCompanyHistory;
use App\Models\Support\BaseModelSupport;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCompany extends Model
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
        'legal_name',
        'trading_name',
        'business_type',
        'product_service_offered',
        'representative_first_name',
        'representative_last_name',
        'email',
        'phone',
        'website',
        'founding_year',
        'company_size',
        'audience',
        'geographical_target',
        'owner_user_id',
        'address1',
        'address2',
        'zipcode_id',
        'city_id',
        'state_id',
        'country_id',
        'lead_approximate_time'
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'legal_name' => 'string',
        'trading_name' => 'string',
        'business_type' => 'string',
        'product_service_offered' => 'string',
        'representative_first_name' => 'string',
        'representative_last_name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'website' => 'string',
        'founding_year' => 'string',
        'company_size' => 'string',
        'audience' => 'string',
        'geographical_target' => 'string',
        'owner_user_id' => 'string',
        'address1' => 'string',
        'address2' => 'string',
        'zipcode_id' => 'string',
        'city_id' => 'string',
        'state_id' => 'string',
        'country_id' => 'string',
        'lead_approximate_time' => 'string'
    ];

    protected $appends = ['representative_name', 'country_name', 'state_name', 'city_name', 'zipcode_name', 'zipcode_code'];

    public $ownerUserId = null;
    public $businessType = [
        'MANUFACTURER',
        'DISTRIBUTOR',
        'SERVICE PROVIDER',
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
            $model->createUserLog($model, 'userActivity.userCompany|created');
            $model->generateHistory($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserLog()
         */
        static::updated(function ($model) {
            if (!auth()->guest()) {
                $model->generateHistory($model);
                $model->createUserLog($model, 'userActivity.userCompany|updated');
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
    public function getOwnerUserId()
    {
        return $this->ownerUserId;
    }

    /**
     * @param null $ownerUserId
     */
    public function setOwnerUserId($ownerUserId): void
    {
        $this->ownerUserId = $ownerUserId;
    }

    /**
     * @return array
     */
    public function getBusinessType(): array
    {
        return $this->businessType;
    }

    /**
     * @param array $businessType
     */
    public function setBusinessType(array $businessType): void
    {
        $this->businessType = $businessType;
    }

    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(UserCompanyHistory::class, "history_of", 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner_user_id", 'uuid');
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

    /**
     * @return BelongsTo
     */
    public function supplierCompany(): BelongsTo
    {
        return $this->belongsTo(OfferDeals::class, "owner_user_id", 'user_id');
    }

    /*==================================================*/
    /* Local Scopes */
    /*==================================================*/

    public function scopeOfUser($query)
    {
        return $query->where('owner_user_id', $this->ownerUserId);
    }

    /*==================================================*/
    /* Accessor and Mutators */
    /*==================================================*/

    /**
     * @return string
     */
    public function getRepresentativeNameAttribute(): string
    {
        return "{$this->representative_first_name} {$this->representative_last_name}";
    }

    public function getCountryNameAttribute(): string
    {
        return $this->country()->exists() ? $this->country->country_name : "";
    }

    public function getStateNameAttribute(): string
    {
        return $this->state()->exists() ? $this->state->state_name : "";
    }

    public function getCityNameAttribute(): string
    {
        return $this->city()->exists() ? $this->city->city_name : "";
    }

    public function getZipcodeNameAttribute(): string
    {
        return $this->zipcode()->exists() ? $this->zipcode->zipcode_name : "";
    }

    public function getZipcodeCodeAttribute(): string
    {
        return $this->zipcode()->exists() ? $this->zipcode->zipcode : "";
    }

    public function getBusinessTypeDropDown()
    {
        return array_combine($this->getBusinessType(), $this->getBusinessType());
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
}
