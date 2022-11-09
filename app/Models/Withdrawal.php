<?php

namespace App\Models;

use App\User;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Withdrawal extends Model
{
    use SoftDeletes , BaseModelSupport;
    protected $ownerUserId;

    protected $fillable = [
        'user_id',
        'uuid',
        'amount',
        'remarks',
        'status'
        //'transaction_type',
    ];

    public $transactionStatus = [
        "PENDING",
        "APPROVED",
        "CANCELED",
    ];
   
    protected $appends = [
        'name',
        'role'
    ];
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
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", 'uuid');
    }

    public function bankDetails() : HasOne
    {
        return $this->hasOne(UserBankDetails::class, 'user_id', 'user_id');
    }
    /*==================================================*/
    /* Getters & Setters */
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

    public function scopeApproved($query)
    {
        return $query->where("status", "APPROVED");
    }

    public function scopeOfUser($query){
        return $query->where('user_id',$this->ownerUserId);
    }
    

    public function getStatusDropDown() : Array
    {

        return array_combine($this->transactionStatus, $this->transactionStatus);
    }

       public function getNameAttribute()
    {

        return $this->user()->exists() ? $this->user->name : "";
    }

      public function getRoleAttribute()
    {

        return $this->user()->exists() ? $this->user->role : "";
    }


}
