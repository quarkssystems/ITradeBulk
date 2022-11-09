<?php



namespace App\Models;



use App\Models\Support\BaseModelSupport;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\SalesOrder;


class WalletTransactions extends Model

{

    use SoftDeletes , BaseModelSupport;

    protected $ownerUserId;



    protected $fillable = [

        'user_id',

        'uuid',

        'credit_amount',

        'debit_amount',

        'remarks',

        'transaction_type',

        'receipt',

        'order_id',
         
        'admin_charge',

        'status',
    ];



    public $transactionStatus = [

        "PENDING",

        "APPROVED",

        "CANCELED",

    ];



    public $transactionType = [

        "EFT",

        "CASH"

    ];

    protected $appends = [
        'order_no',
       
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

      /*==================================================*/
    /* Relational Model */
    /*==================================================*/

 

    public function order()
    {
        return $this->belongsTo(SalesOrder::class,'order_id','uuid');
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

     public function scopeOfAdmin($query, $userId)
    {

        return $query->where("user_id", $userId);

    }




    /**

     * @return array

     */

    public function walletTransactionsCount($user_uuid){

        $walletTotal  = WalletTransactions::where('user_id',$user_uuid)->approved()->get();

        $debitedAmount = $walletTotal->sum('debit_amount');

        $creditedAmount = $walletTotal->sum('credit_amount');

        $totalAmount = $creditedAmount -$debitedAmount;

        $walletCounts = array(

            'debited_amount' => $debitedAmount,

            'credited_amount' => $creditedAmount,

            'total_amount' => $totalAmount,

        );

        return $walletCounts;

    }



    public function getTransactionTypeDropDown()

    {

        return array_combine($this->transactionType, $this->transactionType);

    }

    /* Accessor and Mutators */
    /*==================================================*/

    /**
     * @return string
     */
    public function getOrderNoAttribute() : string
    
    {
          return $this->order()->exists() ? $this->order->order_number : "";
    }

    public function getStatusDropDown() : Array
    {

        return array_combine($this->transactionStatus, $this->transactionStatus);
    }


}

