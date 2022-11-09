<?php

namespace App\Models;

use App\Models\History\UserBankDetailsHistory;
use App\Models\Support\BaseModelSupport;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserBankDetails extends Model
{
    use BaseModelSupport;

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
        'user_id',
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
        'user_id' => 'string',
        'account_confirmation_letter_file' => 'string',
    ];

    protected $appends = ['bank_name','branch_name'];

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
            $model->createUserLog($model, 'userActivity.userBankDetails|created');
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
                $model->createUserLog($model, 'userActivity.userBankDetails|updated');
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
        return $this->hasMany(UserBankDetailsHistory::class, "history_of", 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", 'uuid');
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

    public function scopeOfUser($query)
    {
        return $query->where('user_id', $this->userId);
    }

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/

    public function getAccountTypesDropDown()
    {
        return array_combine($this->getAccountTypes(), $this->getAccountTypes());
    }


     public function getBankNameAttribute() : string
    {
        return $this->bank()->exists() ? $this->bank->name : "";
    }

     public function getBranchNameAttribute() : string
    {
        return $this->bankBranch()->exists() ? $this->bankBranch->branch_name : "";
    }
}
