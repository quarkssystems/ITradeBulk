<?php



namespace App\Models;



use App\Models\Support\BaseModelSupport;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;



class Basket extends Model

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

        'user_id',

        'order_id',

    ];



    /**

     * @var array

     */

    public $casts = [

        'uuid' => 'string',

        'user_id' => 'string',

        'order_id' => 'string',

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

            \Log::info('creating');
//            $model->checkSlug($model);

        });



        /**

         * @see BaseModelSupport::createUserActivity()

         */

        static::created(function ($model) {

            $model->createUserLog($model, 'userActivity.basket|created');
            \Log::info('created');

//            $model->generateHistory($model);

        });



        static::updating(function ($model) {

//            $model->checkSlugUpdate($model);

        });



        /**

         * @see BaseModelSupport::generateHistory()

         * @see BaseModelSupport::createUserActivity()

         */

        static::updated(function($model){

            if(!auth()->guest())

            {

//                $model->generateHistory($model);

                $model->createUserLog($model, 'userActivity.basket|updated');

            }

        });



        static::deleting(function ($model){

            $model->deleteHistory($model);

        });

    }



    /*==================================================*/

    /* Relational Model */

    /*==================================================*/



    public function products()

    {

        return $this->hasMany(BasketProducts::class, 'basket_id', 'uuid');

    }



    public function order()

    {

        return $this->belongsTo(SalesOrder::class, 'order_id', 'uuid');

    }



    /*==================================================*/

    /* Custom Methods */

    /*==================================================*/



    public function createNewBasket()

    {

        return $this->create([

            'user_id' => auth()->check() ? auth()->user()->uuid : null

        ]);

    }

    public function createNewBasketWithUserId($userId)

    {

        return $this->create([

            'user_id' => $userId

        ]);

    }


    public function getTotalProductCount()

    {

        return $this->products()->count()  ? $this->products()->count() : 0;

    }



    public function getBasket()

    {

        $user_id = auth()->check() ? auth()->user()->uuid : null;

        return $this->where('user_id', '=', $user_id)->where('order_id',null )->pluck('uuid');

    }

    public function getBasketforAPI($user_id)

    {   
        return $this->where('user_id', '=', $user_id)->where('order_id',null )->pluck('uuid');

    }

    public function getBasketforRepeatOrder($basket_uuid)

    {   
        return $this->where('uuid', '=', $basket_uuid)->where('order_id','!=',null )->pluck('uuid');

    }

     public function createNewBasketforAPI($user_id)

    {

        return $this->create([

            'user_id' => $user_id

        ]);

    }


    public function getLastOrders($limit= 10) {
        $user_id = auth()->check() ? auth()->user()->uuid : null;
        return $this->where('user_id', '=', $user_id)->where('order_id','!=',null )->orderBy('updated_at','desc')->limit($limit)->get();
    }

    // public function getLastOrders($limit= 10) {
     
    //     $user_id = auth()->check() ? auth()->user()->uuid : null;
     
    //     return $this->leftjoin('sales_orders','sales_orders.user_id','=','baskets.user_id')->where('sales_orders.user_id', '=', $user_id)->where('sales_orders.order_status','=','DELIVERED')->where('sales_orders.payment_status','=','COMPLETED')->orderBy('sales_orders.created_at','desc')->limit($limit)->get();
    
    // }

}