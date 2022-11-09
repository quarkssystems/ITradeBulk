<?php

namespace App;

use DB;
use App\Models\History\UserHistory;
use App\Models\LogisticDetails;
use App\Models\SupplierItemInventory;
use App\Models\Support\BaseModelSupport;
use App\Models\UserBankDetails;
use App\Models\UserCompany;
use App\Models\UserLog;
use App\Models\UserTaxDetails;
use App\Models\WalletTransactions;
use App\Models\UserDocument;
use App\Models\SalesOrder;
use App\Models\Withdrawal;
use App\Models\UserDevices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use SoftDeletes, Notifiable;
    protected $dates = ['deleted_at'];
    protected $perPage = 20;
    protected $ownerUserId;
    /**
     * Model
     * @see \App\User
     *
     * Controller
     * @see \App\Http\Controllers\AdminUserController
     * @see \App\Http\Controllers\AdminManageLogisticsController
     * @see \App\Http\Controllers\AdminManageVendorController
     * @see \App\Http\Controllers\AdminManageSupplierController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminUserRequest
     */

    use Notifiable, BaseModelSupport;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'title',
        'image',
        'first_name',
        'last_name',
        'gender',
        'email',
        'mobile',
        'email_verified_at',
        'password_updated_at',
        'password',
        'logistic_type',
        'transporter_name',
        'logistic_company_id',
        'role',
        'status',
        'latitude',
        'longitude',
        'remarks',
        'facebook_url',
        'twitter_url',
        'insta_url',
        'product_access',
        'fact_access',
        'supplier_id',
        'supplier_delivery',
        'delivery_rate'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'title' => 'string',
        'image' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'gender' => 'string',
        'email' => 'string',
        'mobile' => 'string',
        'email_verified_at' => 'string',
        'password_updated_at' => 'string',
        'password' => 'string',
        'logistic_type' => 'string',
        'transporter_name' => 'string',
        'role' => 'string',
        'status' => 'string',
        'latitude' => 'float',
        'longitude' => 'float',
        'remarks' => 'string',
    ];

    protected $appends = [
        'name',
        'wallet_balance',
        'companyname'
    ];

    public $roles = ['ADMIN', 'VENDOR', 'SUPPLIER', 'PICKER', 'LOGISTICS', 'COMPANY'];
    public $genders = ['MALE', 'FEMALE'];
    public $titles = ['MR', 'MRS', 'MISS', 'MS', 'MX', 'M'];
    public $logisticTypes = ['INDIVIDUAL'];
    // public $logisticTypes = ['INDIVIDUAL', 'COMPANY'];
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
            $model->createUserLog($model, 'userActivity.user|created');
            $model->generateHistory($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserActivity()
         */
        static::updated(function ($model) {
            if (!auth()->guest()) {
                if (!request()->route()->named('logout')) {
                    $model->generateHistory($model);
                }
                $model->createUserLog($model, 'userActivity.user|updated');
            }
        });

        static::deleting(function ($model) {
            $model->deleteHistory($model);
        });
    }

    public function canDelete()
    {
        return false;
    }

    /*==================================================*/
    /* Getter and Setter */
    /*==================================================*/

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return array
     */
    public function getGenders(): array
    {
        return $this->genders;
    }

    /**
     * @param array $genders
     */
    public function setGenders(array $genders): void
    {
        $this->genders = $genders;
    }

    /**
     * @return array
     */
    public function getTitles(): array
    {
        return $this->titles;
    }

    /**
     * @param array $titles
     */
    public function setTitles(array $titles): void
    {
        $this->titles = $titles;
    }

    /**
     * @return array
     */
    public function getLogisticTypes(): array
    {
        return $this->logisticTypes;
    }

    /**
     * @param array $logisticTypes
     */
    public function setLogisticTypes(array $logisticTypes): void
    {
        $this->logisticTypes = $logisticTypes;
    }


    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(UserHistory::class, "history_of", 'uuid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(UserLog::class, "user_id", 'uuid');
    }

    /**
     * @return HasOne
     */
    public function company(): HasOne
    {
        return $this->hasOne(UserCompany::class, 'owner_user_id', 'uuid');
    }
    public function userDevices(): HasOne
    {
        return $this->hasOne(UserDevices::class, 'user_id', 'uuid');
    }

    /**
     * @return HasOne
     */
    public function logisticDetails(): HasOne
    {
        return $this->hasOne(LogisticDetails::class, 'user_id', 'uuid');
    }

    /**
     * @return HasOne
     */
    public function taxDetails(): HasOne
    {
        return $this->hasOne(UserTaxDetails::class, 'user_id', 'uuid');
    }

    /**
     * @return HasOne
     */
    public function bankDetails(): HasOne
    {
        return $this->hasOne(UserBankDetails::class, 'user_id', 'uuid');
    }

    public function supplierTotalStock(): HasMany
    {
        return $this->hasMany(SupplierItemInventory::class, 'user_id', 'uuid');
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransactions::class, 'user_id', 'uuid');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class, 'user_id', 'uuid');
    }

    public function documentUploaded(): HasMany
    {
        return $this->hasMany(UserDocument::class, 'user_id', 'uuid');
    }

    public function driverOrder(): HasMany
    {
        return $this->hasMany(SalesOrder::class, 'logistic_id', 'uuid');
    }

    public function scopeWithDriverOrder($query)
    {
        $query->leftjoin('sales_orders as sales_orders', 'users.uuid', '=', 'sales_orders.logistic_id');
    }

    public function transport_capacity(): HasMany
    {
        return $this->hasMany(LogisticDetails::class, 'user_id', 'uuid');
    }


    public function admin_quick_views()
    {
        return $this->hasOne(AdminQuickView::class);
    }

    /*==================================================*/
    /* Getter & Setter*/
    /*==================================================*/

    public function getUserId()
    {
        return $this->ownerUserId;
    }

    /**
     * @param array $fillable
     */
    public function setUserId($ownerUserId)
    {
        $this->ownerUserId = $ownerUserId;
    }


    /*==================================================*/
    /* Local Scopes */
    /*==================================================*/

    public function scopeUserRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeOfUser($query)
    {
        return $query->where('user_id', $this->user_uuid);
    }
    public function scopeLogisticCompanyId($query, $user_uuid)
    {
        return $query->where('logistic_company_id', $user_uuid);
    }

    public function scopeSupplierIdForPicker($query, $user_uuid)
    {
        return $query->where('supplier_id', $user_uuid);
    }

    public function scopeGetPickerUser($query, $user_uuid)
    {
        return $query->where('role', 'PICKER')->where('supplier_id', $user_uuid);
    }

    public function scopeGetDispatcherUser($query, $user_uuid)
    {
        return $query->where('role', 'DISPATCHER')->where('supplier_id', $user_uuid);
    }

    /*==================================================*/
    /* JWT methods */
    /*==================================================*/

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /*==================================================*/
    /* Accessor and Mutators */
    /*==================================================*/

    /**
     * @return string
     */
    public function getNameAttribute(): string

    {
        return "{$this->first_name} {$this->last_name}";
    }


    /**
     * @return string
     */
    public function getCompanyNameAttribute(): string

    {
        return "{$this->transporter_name}";
    }

    public function getSupplierTotalStockAttribute()
    {

        $stockData = $this->supplierTotalStock()->where('user_id', auth()->user()->uuid)->get();
        return [
            'single' => $stockData->sum('single'),
            'shrink' => $stockData->sum('shrink'),
            'case' => $stockData->sum('case'),
            'pallet' => $stockData->sum('pallet'),
        ];
    }

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

    public function getGenderDropDown()
    {
        return array_combine($this->getGenders(), $this->getGenders());
    }

    public function getRoleDropDown()
    {
        return array_combine($this->getRoles(), $this->getRoles());
    }

    public function getTitleDropDown()
    {
        return array_combine($this->getTitles(), $this->getTitles());
    }


    public function getWalletBalanceAttribute()
    {
        if ($this->role == 'COMPANY' && $this->logistic_type == 'COMPANY') {
            $creditedAmount = WalletTransactions::leftjoin('users', 'wallet_transactions.user_id', '=', 'users.uuid')->select(DB::raw("SUM(credit_amount) as creditAmount"))->where('users.logistic_company_id', '=', $this->uuid)->first();

            $debitedAmount = $this->walletTransactions()->where('status', 'APPROVED')->sum("debit_amount");

            $withdrawalAmount = $this->withdrawals()->where('status', 'PENDING')->sum("amount");

            return $creditedAmount->creditAmount - $debitedAmount - $withdrawalAmount;

            // return round($wallet_balance,2);

        } else {
            return ($this->walletTransactions()->where('status', 'APPROVED')->sum("credit_amount") - $this->walletTransactions()->where('status', 'APPROVED')->sum("debit_amount") - $this->walletTransactions()->where('status', 'PENDING')->sum("debit_amount") - $this->withdrawals()->where('status', 'PENDING')->sum("amount"));

            // return round($wallet_balance,2);

        }
    }

    /**
     * @param $message
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function logActivity($message): Model
    {
        return $this->logs()->create(['log' => $message]);
    }

    public function getBankDetailByUserId($userId)
    {
        return $this->bankDetails()->where('uuid', $userId)->get();
    }

    public function getDrivingDistance($lat1, $long1, $lat2, $long2)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?destinations=" . $lat2 . "," . $long2 . "&origins=" . $lat1 . "," . $long1 . "&units=imperial&key=" . config("app.map_api_key");
        // $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $lat1 . "," . $long1 . "&destinations=" . $lat2 . "," . $long2 . "&mode=driving&key=" . config("app.map_api_key");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        $dist = isset($response_a['rows'][0]['elements'][0]['distance']['text']) ? $response_a['rows'][0]['elements'][0]['distance']['text'] : 0;
        $time = isset($response_a['rows'][0]['elements'][0]['duration']['text']) ? $response_a['rows'][0]['elements'][0]['duration']['text'] : 0;

        // $dist = rand(10,20);
        // $time = rand(10,20);

        return array('distance' => $dist, 'time' => $time);
    }

    /*public function getCompanyNameAttribute()
    {
        return $this->company()->exists() ? $this->company()->first()->legal_name : $this->name;
    }*/

    public static function getbyrole()
    {
    }
    public function getDropDownSuppiler($except = null)
    {
        return $this->where('uuid', '!=', $except)->Where('role', 'SUPPLIER')->orderBy('created_at')->pluck('first_name', 'uuid');
    }

    public function getDropDownDriver($except = null)
    {
        return $this->where('uuid', '!=', $except)->whereHas('documentUploaded', function ($q) {
            $q->where('approved', '=', "YES");
        })->Where('role', 'DRIVER')->where('status', 'ACTIVE')->orderBy('created_at')->pluck('first_name', 'uuid');
    }


    public function getDropDown($except = null)
    {
        return $this->where('uuid', '!=', $except)->where('role', 'ADMIN')->orWhere('role', 'SUPPLIER')->orderBy('created_at')->pluck('first_name', 'uuid');
    }

    public function getLogisticCompanyDropDown($except = null)
    {
        return $this->where('uuid', '!=', $except)->Where('role', 'COMPANY')->orderBy('created_at')->pluck('transporter_name', 'uuid');

        //return \App\Models\LogisticCompany::orderBy('created_at')->pluck('first_name', 'uuid');
    }

    public function getCompanyName($companyid)
    {
        //$except = null;    
        return $this->Where('uuid', $companyid)->pluck('transporter_name')->first();
    }
}
