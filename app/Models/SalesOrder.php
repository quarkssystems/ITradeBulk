<?php



namespace App\Models;



use App\Models\History\SalesOrderHistory;

use App\Models\OrderLogisticQueue;

use App\Models\Support\BaseModelSupport;

use App\User;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\LogisticDetails;

use DB;

use Illuminate\Database\Eloquent\SoftDeletes;



class SalesOrder extends Model

{

    use BaseModelSupport;

    use SoftDeletes;



    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $fillable = [

        'uuid',

        'order_id',

        'user_id',

        'supplier_id',

        'logistic_id',

        'picker_id',

        'dispatcher_id',

        'cart_amount',

        'shipment_amount',

        'tax_amount',

        'discount_amount',

        'final_total',

        'order_status',

        'payment_status',

        'delivery_type',

        'distance',

        'total_weight',

        // new added
        'order_lead_time',
        'order_lead_time_clock',
        'order_lead_time_to_clock',
        'picker_id',
        'dispatcher_id',
        'logistic_details_id',
        'delivery_requested'
    ];



    /**

     * @var array

     */

    public $casts = [

        'uuid' => 'string',

        'order_id' => 'string',

        'user_id' => 'string',

        'supplier_id' => 'string',

        'logistic_id' => 'string',

        'cart_amount' => 'float',

        'shipment_amount' => 'float',

        'discount_amount' => 'float',

        'tax_amount' => 'float',

        'final_total' => 'float',

        'order_status' => 'string',

        'payment_status' => 'string',

        'delivery_type'  => 'string',

        'total_weight' => 'string',

        'distance' => 'string',

        // new added
        'order_lead_time' => 'date'

    ];



    protected $appends = [

        "user_name",

        "supplier_name",

        "logistic_name",

        "order_number",


    ];

    const ORDERPLACED = 'ORDER PLACED';
    const CHOOSEPICKER = 'CHOOSE PICKER';
    const ORDERPACKED = 'ORDER PACKED';
    const ORDERSTATUSCHANGED = 'ORDER STATUS CHANGED';
    const DISPATCH = 'DISPATCH';
    const ACCEPTORDER = 'ACCEPT ORDER';



    public $order_status_supplier = [

        "--SELECT ORDER STATUS--",

        "ACCEPT ORDER", //new added

        // "CHOOSE PICKER",

        "PICKING STARTED", //new added

        "PACKED",

        "DISPATCH",
        // "DISPATCHED",

        "ORDER COMPLETE", //new added

        "DELIVERED",

        "CANCELLED"
    ];

    public $order_status_supplier_delivery = [

        "--SELECT ORDER STATUS--",

        "ACCEPT ORDER", //new added

        // "CHOOSE PICKER",

        "PICKING STARTED", //new added

        "PACKED",

        "DISPATCH",
        // "DISPATCHED",

        "ORDER COLLECTED", //new added

        "ORDER COMPLETE", //new added

        "DELIVERED",

        "CANCELLED"
    ];



    public $order_status_vendor = [

        "--SELECT ORDER STATUS--",

        "CANCELLED"

    ];

    public $order_status_driver = [

        "--SELECT ORDER STATUS--",

        "ACCEPT DELIVERY", //new added

        "ORDER COLLECTED", //new added

        "DELIVERED",
        // "ORDER DELIVERED", //new added
    ];

    public $order_status_picker = [

        "--SELECT ORDER STATUS--",

        "PICKING STARTED", //new added

        "PACKED", //new added
    ];

    public $order_status_dispatcher = [

        "--SELECT ORDER STATUS--",

        "DISPATCH", //new added
    ];



    public $orderStatus = [

        "ORDER PLACED",
        // "PLACED",

        "PACKED",

        "DISPATCH",
        // "DISPATCHED",

        "DELIVERED",

        "CANCELLED"

    ];



    public $paymentStatus = [

        "PENDING",

        "COMPLETED",

        "ON_HOLD",

        "CANCELLED"

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

         * @see User::setUserType()

         * @see User::setClientId()

         */

        static::creating(function ($model) {

            $model->addUUID($model);

            $model->addOrderId($model);



            // $model->checkSlug($model);

        });



        /**

         * @see BaseModelSupport::createUserActivity()

         */

        static::created(function ($model) {

            $model->createUserLog($model, 'userActivity.salesOrder|created');

            $model->generateHistory($model);
        });



        static::updating(function ($model) {

            // $model->checkSlugUpdate($model);

        });



        /**

         * @see BaseModelSupport::generateHistory()

         * @see BaseModelSupport::createUserActivity()

         */

        static::updated(function ($model) {

            if (!auth()->guest()) {

                $model->generateHistory($model);

                $model->createUserLog($model, 'userActivity.salesOrder|updated');
            }
        });



        static::deleting(function ($model) {

            $model->deleteHistory($model);
        });
    }



    public function addOrderId($model): void

    {

        $model->order_id = $this->count() + 1;
    }



    /*==================================================*/

    /* Relational Model */

    /*==================================================*/

    public function drivers()

    {

        return $this->hasMany(OrderLogisticQueue::class, 'order_id', 'uuid');
    }

    /**

     * @return \Illuminate\Database\Eloquent\Relations\HasMany

     */

    public function history(): HasMany

    {

        return $this->hasMany(SalesOrderHistory::class, "history_of", 'uuid');
    }





    /*==================================================*/

    /* Custom Methods */

    /*==================================================*/



    public function user()

    {

        return $this->belongsTo(User::class, "user_id", 'uuid');
    }



    public function supplier()

    {

        return $this->belongsTo(User::class, "supplier_id", 'uuid');
    }



    public function logistic()

    {

        return $this->belongsTo(User::class, "logistic_id", 'uuid');
    }



    public function logisticDetails()

    {

        return $this->belongsTo(LogisticDetails::class, 'logistic_id', 'user_id');
    }





    public function basket()

    {

        return $this->hasOne(Basket::class, "order_id", "uuid");
    }



    public function orderstatusUpdate()

    {

        return $this->hasMany(OrderstatusUpdate::class, "sales_id", "uuid");
    }



    public function scopeOfUser($query, $userId)

    {

        return $query->where("user_id", $userId);
    }



    public function scopeOfSupplier($query, $userId)

    {

        return $query->where("supplier_id", $userId);
    }

    public function scopeOfDriver($query, $userId)

    {

        return $query->where("logistic_id", $userId);
    }

    public function scopeOfCompany($query, $userId)
    {
        return $query->leftjoin('users', 'users.uuid', '=', 'sales_orders.logistic_id')->where('users.logistic_company_id', '=', $userId);
    }

    public function scopeOfDispatcher($query, $userId)
    {
        return $query->where('sales_orders.dispatcher_id', '=', $userId)->orwhere('sales_orders.dispatcher_id', '=', 'select_all');
    }

    public function scopeOfPicker($query, $userId)
    {
        return $query->where('sales_orders.picker_id', '=', $userId)->orwhere('sales_orders.picker_id', '=', 'select_all');
    }

    // public function getCompanyPendingOrders()
    // {
    //     return $this->leftjoin('users','users.uuid','=','sales_orders.logistic_id')->where('users.logistic_company_id','=',auth()->user()->uuid)->where('sales_orders.payment_status','=','PENDING')->get();
    // }

    // public function getPendingOrders()
    // {
    //     return $this->leftjoin('users','users.uuid','=','sales_orders.logistic_id')->where('sales_orders.logistic_id','=',auth()->user()->uuid)->where('sales_orders.payment_status','=','PENDING')->get();
    // }

    // public function getCompletedOrders()
    // {
    //     return $this->leftjoin('users','users.uuid','=','sales_orders.logistic_id')->where('sales_orders.logistic_id','=',auth()->user()->uuid)->where('sales_orders.payment_status','=','COMPLETED')->get();
    // }

    // public function getCompanyCompletedOrders()
    // {
    //     return $this->leftjoin('users','users.uuid','=','sales_orders.logistic_id')->where('users.logistic_company_id','=',auth()->user()->uuid)->where('sales_orders.payment_status','=','COMPLETED')->get();
    // }

    public function getCompanyPendingOrders()
    {
        return $this->leftjoin('users', 'users.uuid', '=', 'sales_orders.logistic_id')->where('users.logistic_company_id', '=', auth()->user()->uuid)->where('sales_orders.payment_status', '=', 'PENDING')->get();
    }

    public function getPendingOrders()
    {
        return $this->leftjoin('users', 'users.uuid', '=', 'sales_orders.logistic_id')->where('sales_orders.logistic_id', '=', auth()->user()->uuid)->where('sales_orders.payment_status', '=', 'PENDING')->get();
    }

    public function getCompletedOrders()
    {
        return $this->leftjoin('users', 'users.uuid', '=', 'sales_orders.logistic_id')->where('sales_orders.logistic_id', '=', auth()->user()->uuid)->where('sales_orders.order_status', '=', 'DELIVERED')->where('sales_orders.payment_status', '=', 'COMPLETED')->get();
    }

    public function getCompanyCompletedOrders()
    {
        return $this->leftjoin('users', 'users.uuid', '=', 'sales_orders.logistic_id')->where('users.logistic_company_id', '=', auth()->user()->uuid)->where('sales_orders.order_status', '=', 'DELIVERED')->where('sales_orders.payment_status', '=', 'COMPLETED')->get();
    }

    public function getUserNameAttribute()

    {

        return $this->user()->exists() ? $this->user->name : "";
    }



    public function getUserAddressAttribute()

    {

        return $this->user()->exists() && $this->user->company()->exists() ? $this->user->company->address : [];
    }

    public function getUserDetailsAttribute()

    {

        return $this->user()->exists() && $this->user->company()->exists() ? $this->user->company : [];
    }

    public function getSupplierNameAttribute()

    {

        return $this->supplier()->exists() ? $this->supplier->name : "";
    }



    public function getSupplierAddressAttribute()

    {

        return $this->supplier()->exists() && $this->supplier->company()->exists() ? $this->supplier->company->address : [];
    }



    public function getLogisticAddressAttribute()

    {

        return $this->logistic()->exists() && $this->logistic->logisticDetails()->exists() ? $this->logistic->logisticDetails->address : [];
    }

    public function getLogisticNameAttribute()

    {

        return $this->logistic()->exists() ? $this->logistic->name : "";
    }



    public function getOrderNumberAttribute()

    {

        return str_pad($this->order_id, 7, "0", STR_PAD_LEFT);
    }



    /**

     * @return array

     */

    public function getOrderStatus(): array

    {


        if (auth()->user()->role) {

            switch (auth()->user()->role) {

                case 'ADMIN':

                    return $this->orderStatus;

                    break;



                case 'SUPPLIER':

                    // return $this->order_status_supplier; 

                    if ($this->delivery_type != 'delivery') {
                        return $this->order_status_supplier;
                    } else {
                        return $this->order_status_supplier_delivery;
                    }

                    break;



                case 'VENDOR':

                    return $this->order_status_vendor;

                    break;



                case 'DRIVER':

                    return $this->order_status_driver;

                    break;

                case 'PICKER':

                    return $this->order_status_picker;

                    break;

                case 'DISPATCHER':

                    return $this->order_status_dispatcher;

                    break;


                case 'COMPANY':

                    return $this->order_status_driver;

                    break;
            }
        }
    }

    public function getOrderStatusApi(): array

    {

        return $this->orderStatus;
    }



    /**

     * @param array $orderStatus

     */

    public function setOrderStatus(array $orderStatus): void

    {

        $this->orderStatus = $orderStatus;
    }



    public function getOrderStatusDropdown()

    {

        return array_combine($this->getOrderStatus(), $this->getOrderStatus());
    }

    public function getOrderStatusDropdownApi()

    {

        return array_combine($this->getOrderStatusApi(), $this->getOrderStatusApi());
    }



    public function getBasketItemsAttribute()

    {

        if ($this->basket()->exists() && $this->basket->products()->exists()) {

            return $this->basket->products;
        }

        return [];
    }
}
