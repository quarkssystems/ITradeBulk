<?php

namespace App\Models;

use App\Models\History\UserTaxDetailsHistory;
use App\Models\Support\BaseModelSupport;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTaxDetails extends Model
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
        'tax_number',
        'vat_number',
        'passport_number',
        'passport_document_file',
        'verify_tax_details',
        'user_id',
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'tax_number' => 'string',
        'vat_number' => 'string',
        'passport_number' => 'string',
        'passport_document_file' => 'string',
        'verify_tax_details' => 'string',
        'user_id' => 'string',
    ];


    public $userId = null;

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
            $model->createUserLog($model, 'userActivity.userTaxDetail|created');
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
                $model->createUserLog($model, 'userActivity.userTaxDetail|updated');
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
     * @param null $ownerUserId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history() : HasMany
    {
        return $this->hasMany(UserTaxDetailsHistory::class, "history_of", 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", 'uuid');
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

    public function getVerifyTaxDetailsDropDown()
    {
        return ['NO' => 'NOT READY', 'YES' => 'PLEASE VERIFY'];
    }
}
