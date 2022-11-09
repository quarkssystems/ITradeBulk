<?php



namespace App\Models;



use App\Models\History\OrderLogisticQueueHistory;

use App\Models\Support\BaseModelSupport;

use App\User;

use DB;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;



class OrderLogisticQueue extends Model

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

        'order_id',

        'vendor_id',

        'supplier_id',

        'driver_id',

        'distance',

        'status'

    ];



    /**

     * @var array

     */

    public $casts = [

        'uuid' => 'string',

        'order_id' => 'string',

        'vendor_id' => 'string',

        'supplier_id' => 'string',

        'driver_id' => 'string',

        'distance' => 'float',

        'status' => 'string'

    ];



    protected $appends = [

        "vendor_name",

        "supplier_name",

        "dirver_name",

        "order_number"

    ];

    protected $table = 'order_logistic_queue';


     public $orderStatus = [
        "ACCEPT",

        "REJECT",
        
        "OCCUPIED",
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

            $model->createUserLog($model, 'userActivity.OrderLogisticQueue|created');

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

                $model->createUserLog($model, 'userActivity.OrderLogisticQueue|updated');

            }

        });



        static::deleting(function ($model){

            $model->deleteHistory($model);

        });

    }



    public function addOrderId($model) : void

    {

        $model->order_id = $this->count() + 1;

    }



    /*==================================================*/

    /* Relational Model */

    /*==================================================*/



    /**

     * @return \Illuminate\Database\Eloquent\Relations\HasMany

     */

    public function history() : HasMany

    {



        return $this->hasMany(OrderLogisticQueueHistory::class, "history_of", 'uuid');

    }



    /*==================================================*/

    /* Custom Methods */

    /*==================================================*/



    public function vendor()

    {

        return $this->belongsTo(User::class, "vendor_id", 'uuid');

    }



    public function supplier()

    {

        return $this->belongsTo(User::class, "supplier_id", 'uuid');

    }



    public function driver() //logistic

    {

        return $this->belongsTo(User::class, "driver_id", 'uuid');

    }



    public function order()

    {

        return $this->belongsTo(SalesOrder::class, "order_id", "uuid");

    }



    public function scopeOfUser($query, $userId)

    {

        return $query->where("user_id", $userId);

    }



    public function scopeOfSupplier($query, $userId)

    {

        return $query->where("supplier_id", $userId);

    }



    public function getUserNameAttribute()

    {

        return $this->vendor()->exists() ? $this->user->name : "";

    }



    public function getUserAddressAttribute()

    {

        return $this->user()->exists() && $this->user->company()->exists() ? $this->user->company->address : [];

    }



    public function getSupplierNameAttribute()

    {

        return $this->supplier()->exists() ? $this->supplier->name : "";

    }



    public function getSupplierAddressAttribute()

    {

        return $this->supplier()->exists() && $this->supplier->company()->exists() ? $this->supplier->company->address : [];

    }



    public function getDriverNameAttribute()

    {

        return $this->driver()->exists() ? $this->driver->name : "";

    }



    public function getOrderNumberAttribute()

    {

        return $this->order()->exists() ? str_pad($this->order->order_id, 7, "0", STR_PAD_LEFT) : "";

    }



     public function getlatitudeAttribute()

    {

        return $this->supplier()->exists() ? $this->supplier->latitude : "";

    }





    /**

     * @return array

     */

    public function getStatus(): array

    {

        return $this->orderStatus;

    }



    /**

     * @param array $Status

     */

    public function setStatus(array $status): void

    {

        $this->orderStatus = $status;

    }



    public function getStatusDropdown()

    {

        return array_combine($this->getStatus(), $this->getStatus());

    }

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function scopeOfOrder($query, $orderId){
        return $query->orderBy('updated_at','desc')->groupBy('order_id');
    }

    public static function getDrivers($orderId){

        return DB::table('users')->leftjoin('order_logistic_queue', 'users.uuid','=','order_logistic_queue.driver_id')->select('order_logistic_queue.status','order_logistic_queue.driver_id',DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'))->where('order_logistic_queue.order_id','=',$orderId)->get();
    }



}

