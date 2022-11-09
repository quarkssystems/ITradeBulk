<?php

namespace App\Models;

use App\Models\History\LogisticCompanyBankDetailsHistory;
use App\Models\Support\BaseModelSupport;
use App\Models\LogisticCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LogisticCompanyBankDetails extends Model
{
    use BaseModelSupport;

    protected $tabel = 'logistic_company_bank_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'bank_account_name',
        'bank_account_number',
        'bank_account_type',
        'bank_id',
        'bank_branch_id',
        'logistic_company_id',
        'account_confirmation_letter_file',
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'bank_account_name' => 'string',
        'bank_account_number' => 'string',
        'bank_account_type' => 'string',
        'bank_id' => 'string',
        'bank_branch_id' => 'string',
        'logistic_company_id' => 'string',
        'account_confirmation_letter_file' => 'string',
    ];

    public $userId = null;

    public $accountTypes = [
        'CHEQUE',
        'SAVING',
        'TRANSMISSION',
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
            $model->createUserLog($model, 'userActivity.logisticCompanyBankDetails|created');
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
                $model->createUserLog($model, 'userActivity.logisticCompanyBankDetails|updated');
            }
        });

        static::deleting(function ($model){
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
    public function getAccountTypes(): array
    {
        return $this->accountTypes;
    }

    /**
     * @param array $accountTypes
     */
    public function setAccountTypes(array $accountTypes): void
    {
        $this->accountTypes = $accountTypes;
    }

    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history() : HasMany
    {
        return $this->hasMany(LogisticCompanyBankDetailsHistory::class, "history_of", 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function logistic_company() : BelongsTo
    {
        return $this->belongsTo(LogisticCompany::class, "logistic_company_id", 'uuid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bank() : BelongsTo
    {
        return $this->belongsTo(BankMaster::class, 'bank_id', 'uuid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bankBranch() : BelongsTo
    {
        return $this->belongsTo(BankBranch::class, 'bank_branch_id', 'uuid');
    }

    /*==================================================*/
    /* Local Scopes */
    /*==================================================*/

    public function scopeOfLogisticCompany($query)
    {
        return $query->where('logistic_company_id', $this->logistic_company_id);
    }

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

    public function getAccountTypesDropDown()
    {
        return array_combine($this->getAccountTypes(), $this->getAccountTypes());
    }
}
