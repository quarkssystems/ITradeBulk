<?php

namespace App\Models;

use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;

class Otpgenerate extends Model
{
    use BaseModelSupport;
  

    protected $dates = ['deleted_at'];
    protected $perPage = 20;
    protected $table = 'otp_generate';
    /**
     * The attributes that are mass assofferdealsignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'sales_id',
        'sender_id',
        'receiver_id',
        'otp',
        'status',
        'attempt'

    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'sales_id' => 'string',
        'sender_id' => 'string',
        'receiver_id' => 'string',
        'otp' => 'string',
        'status' => 'string',
        'attempt' => 'integer'
    ];


     protected $attributes = [
        'attempt' => 0
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
            $model->otp =$model-> generateOTPNumber($model);   
            $model->addUUID($model);
           // $model->checkSlug($model);
        });

       
    }

    function generateOTPNumber($model) {
        $number = mt_rand(10000, 99999); // better than rand()

        // call the same function if the barcode exists already
        if ($model->OTPExists($number)) {
            return $model->generateOTPNumber();
        }

        // otherwise, it's valid and can be used
        return $number;
    }

    function OTPExists($number) {
        // query the database and return a boolean
        // for instance, it might look like this in Laravel
        return $this->where('otp',$number)->exists();
    }

}
