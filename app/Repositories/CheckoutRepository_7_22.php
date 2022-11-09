<?php

namespace App\Repositories;

use App\Exceptions\CustomeException;
use App\General\General;
use App\Models\Product;
use App\Models\SupplierItemInventory;

use App\Models\UserDocument;
use App\Models\Basket;
use App\Models\LogisticDetails;
use App\Models\DeliveryVehicleMaster;
use App\Models\OfferDeals;
use App\Models\OffercodeUsedby; 
use App\Models\WalletTransactions;
use App\Models\SalesOrder;
use Auth;
use App\Repositories\BaseRepository;
use App\User;
use DB;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;
use App\General\ChangeOrderStatus;
use Illuminate\Support\Collection;



/**
 * Class ProductRepository.
 */
class CheckoutRepository_7_22 extends BaseRepository
{
    use ResetsPasswords;

    public function model()
    {
      return Product::class;
    }


    public function addtocart($data)
    {
        $user_id = $data['user_uuid'];
        $productId = $data['product_id'];
        $single_qty = $data['qty']; 

        $userdoc = New UserDocument; 
        $user = New User;

        $users = $user->where('uuid', $user_id)->first();
        if($users){
          if(!$userdoc->getDocumentStatusAPI($user_id,$users->role)){
            return "error";
         }  
        }

        $basketModel = New Basket;
        
        $basket_data = $basketModel->getBasketforAPI($user_id);
        if($basket_data->first())
          {
              $basketId = $basket_data['0'];    
          }
          else
          {
              $basketId = $basketModel->createNewBasketforAPI($user_id)->uuid;
          }

          $basket = $basketModel->where('uuid', $basketId)->first();
       
          if($basket->products()->where('product_id', $productId )->count() == 0)
          {
             
              $basket->products()->create(['product_id' => $productId, 'single_qty' => $single_qty]); // attribute  single_qty  model
          }
          else
          { 
              $basket->products()->where('product_id', $productId )->update(['product_id' => $productId, 'single_qty' => $single_qty]);
              
          }

         return 'success';

     } 


     /*Get Cart product data */
     public function cart($data)
    {
        $your_cart = array();
        $basketModel = New Basket;
        $user_id = $data['user_uuid'];

         $basket_data = $basketModel->getBasketforAPI($user_id);
        if($basket_data->first())
          {
              $basketId = $basket_data['0'];    
              $basket = $basketModel->where('uuid', $basketId)->first();
              $products =  $basket->products;
              foreach($products as $key => $cartProduct)
                {
                  $your_cart[$key]['product_id'] = $cartProduct->product_id; 
                  $your_cart[$key]['product_name'] = $cartProduct->product->name; 
                  $your_cart[$key]['product_base_image'] = url('/').$cartProduct->product->base_image; 
                  $your_cart[$key]['product_qty'] = $cartProduct->single_qty; 
                  $your_cart[$key]['stock_type'] = $cartProduct->product->stock_type; 
             
               }         
          } 

        if(empty($your_cart))
         {
              $result = [];
         }
         else
         {
            $result = $your_cart;
         }
              
        return $result;
   }

    public function removetocart($data)
    {
        $user_id = $data['user_uuid'];
        $productId = $data['product_id'];
       
        $basketModel = New Basket;
        
        $basket_data = $basketModel->getBasketforAPI($user_id);
        if($basket_data->first())
          {
              $basketId = $basket_data['0'];
              $basket = $basketModel->where('uuid', $basketId)->first();
              $basket->products()->where('product_id', $productId)->delete();
          }
         return 'data';

     } 



     /*Get Cart product data */
     public function selectSupplier($data)
    {

    	
        $supplierLoopData = [];
        $basketModel = New Basket;
        $supplierItemInventoryModel = New SupplierItemInventory;
        $userModel = New User;
        $logisticModel = New LogisticDetails; 
        $deliveryVehicleMasterModel = New DeliveryVehicleMaster; 
        $user_id = $data['user_uuid'];
        
        $currentUser = $userModel->where('uuid',$user_id)->first();
        
        $dWeight ="";
        $basket_data = $basketModel->getBasketforAPI($user_id);
       	 

        if($basket_data->first())
          {

              //Get backetid and get product of cart
              $basketId = $basket_data['0'];    
              $basket = $basketModel->where('uuid', $basketId)->first();
              $basketProducts = $basket->products;
           
              if(!$basketProducts->isEmpty()){
                
                  $arrBasketProducts = array();
                  foreach($basketProducts as $productKey => $productData){
                      if (!empty($productData['single_qty']) && $productData['single_qty'] >=0){
                          $arrBasketProducts[] = $productData;
                          $basketProductIds[] = $productData['product_id'];
                      }
                  } 
                
                $supplierIdsWithStockModal = $supplierItemInventoryModel->whereIn('product_id', $basketProductIds);
                $supplierIdsWithStockModal->where(function($q) {
                    $q->whereNotNull('single');
                    $q->where('single', '>', 0);
                    $q->whereNotNull('single_price');
                    $q->where('single_price', '>', 0);
                });
                $supplierIdsWithStock = $supplierIdsWithStockModal->groupBy('user_id')->pluck('user_id');
                
                //dd(DB::getQueryLog()); // Show results of log
               // $supplierLoopData = [];
                $suppliers = $userModel->whereIn('uuid', $supplierIdsWithStock)->with('company')->get();

                foreach($suppliers as $supplierindex => $supplier){

                  if(!empty($supplier->latitude) && !empty($supplier->longitude)){

                   
                    $supplierLoopData[$supplierindex]["supplier_display"] = $supplier->company()->exists() ? $supplier->company->trading_name : $supplier->name;
                    $supplierLoopData[$supplierindex]["supplier_uuid"] = $supplier->uuid;
                    if($supplier->image !=''){
                        $supplierLoopData[$supplierindex]["supplier_image"] = url('/').$supplier->image;
                    }else{
                        $supplierLoopData[$supplierindex]["supplier_image"] ="";
                    }
                    //$supplierLoopData[$supplierindex]["products"] = [];

                    //$supplierLoopData[$supplierindex]["product_total_weight_unit"] = [];
                   // $supplierLoopData[$supplierindex]["total"] = [];
                    //$supplierLoopData[$supplierindex]["delivery_type"] = [];

                    $totalWeightUnit =  [];
                    $total = 0;
                    $totalWeight = 0;
                    $totalProducts = 0;
                    $totalAvailableProducts = 0;
                    $itemTotalTax = 0;
                    $distanceValue = 0;
                     foreach($arrBasketProducts as $proIndex => $basketProduct)
                    {
                        $totalProducts++;
                        $rowTotal = 0;


                        if(!empty($basketProduct->single_qty) && $basketProduct->single_qty > 0)
                        {
                            $supplierItemInventoryDataModel = $supplierItemInventoryModel->where('product_id', $basketProduct->product_id)->where('user_id', $supplier->uuid);
                            if ($basketProduct->single_qty > 0) {
                                $supplierItemInventoryModel->where('single', '>', 0);
                            }
                            $supplierItemInventoryDataModel->whereNotNull('single_price')->where('single_price', '>', 0);
                            $supplierLatestRate = $supplierItemInventoryDataModel->first();
                            
                           
                            if($supplierLatestRate && !empty($supplierLatestRate->single_price) && $supplierLatestRate->single_price > 0){
                           
                                $singlePrice = $supplierLatestRate->single_price;
                              
                                $itemWeight = 0;
                                $productitemTax = 0;
                                $totalAvailableProducts++;

                                $productName = $supplierLatestRate->product->name;
                                $supplierProductLoopData["product_name"] = $productName;
                                $supplierProductLoopData["stock"] = [];
                                if($basketProduct->single_qty > 0) {
                                    $supplierProductLoopData["stock"]["single"]["qty"] = $basketProduct->single_qty;
                                    $supplierProductLoopData["stock"]["single"]["price"] = $singlePrice;
                                    
                                    $itemWeight = $basketProduct->product->getCalculatedWeight("single", $basketProduct->single_qty);
                                    $rowTotal = ($basketProduct->single_qty * $singlePrice);
                                    $productitemTax = $basketProduct->product->getCalculatedTax("single", $basketProduct->single_qty, $singlePrice);

                                    $supplierProductLoopData["stock"]["single"]["row_total_price"] = $rowTotal;
                                    $supplierProductLoopData["stock"]["single"]["weight"] = $itemWeight;
                                    $supplierProductLoopData["stock"]["single"]["tax"] = $productitemTax;
                                    
                                }
                              
                                $totalWeight += $itemWeight;
                                $itemTotalTax += $productitemTax;
                                $total += $rowTotal;
                                
                                // $supplierLoopData[$supplierindex]["products"][$proIndex] = $supplierProductLoopData;

                            }    
                        }   
                    }


                               
                        
                        //$supplierLoopData[$supplierindex]["total_product_weight"] = $totalWeight;
                       

                        $supplierLoopData[$supplierindex]["product_price"] = $total;
                        $supplierLoopData[$supplierindex]["total_tax"] = $itemTotalTax;


                        $totalWeightUnit = $supplier->kgToUnit($totalWeight);
                        
                         $supplierLoopData[$supplierindex]["total_weight"] = $totalWeight .' '. $totalWeightUnit["unit"];
                        //$supplierLoopData[$supplierindex]["weight"] = $totalWeightUnit["weight"];

                      //  $supplierLoopData[$supplierindex]["unit"] = $totalWeightUnit["unit"];

                        $supplierLoopData[$supplierindex]["total_available_products"] = $totalAvailableProducts;
                        $availablePercentage = intval((100 * $totalAvailableProducts) / $totalProducts);
                        $supplierLoopData[$supplierindex]["available_percentage_product"] = $availablePercentage.'% Products Available';  


                        
                        if(($totalWeightUnit["unit"] == 'ton' && $totalWeightUnit["weight"] < 1 ) || $totalWeightUnit["unit"] != 'ton') 
                        {

                         
                          $supplierLoopData[$supplierindex]["message_ton"] ="Please do order Min 1 Ton , Continue more shopping or Are you continue to  paying for 1 Ton Delivery Charge."; 
                          $dWeight = 1000;
                        }
                        else
                        {
                          $dWeight =$totalWeightUnit["weight"] * 1000;
                        }
                      
                        $distance = $supplier->getDrivingDistance($currentUser->latitude, $currentUser->longitude, $supplier->latitude, $supplier->longitude);  

                        $distanceValue = isset($distance['distance']) ? $distance['distance'] : 0;

                        $deliveryDetails = $deliveryVehicleMasterModel->getDeliveryPrice($dWeight, $distanceValue);
          
                         $available_list = $logisticModel->getVehicle($dWeight);
                          
                          $vehicle_list= array();

                          if($available_list!=""){
                            foreach ($available_list as $key1 => $value1){

                                    $vehicle_list[] = $value1->vehicle_type;
                            }
                        }


                  


                         //$supplierLoopData[$supplierindex]["delivery_type"] = [] ;
                         //$supplierLoopData[$supplierindex]["delivery_type"]['pickup'] = [];
                      
                        // $supplierLoopData[$supplierindex]["delivery_type"]['delivery'] = [];
                         $supplierLoopData[$supplierindex]['total_distance'] = $distanceValue;
                         $supplierLoopData[$supplierindex]['delivery_vehicle'] = implode(' OR ', array_unique($vehicle_list));

                          $supplierLoopData[$supplierindex]['delivery_descrition'] = implode(' OR ', array_unique($vehicle_list));

                          $supplierLoopData[$supplierindex]['approximate_pallet_capacity'] =isset($deliveryDetails['palletCapacity']) ? $deliveryDetails['palletCapacity'] : null;

                       
                         $supplierLoopData[$supplierindex]['delivery_charge'] =isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0;

                        $supplierLoopData[$supplierindex]['product_charge'] = number_format(($total + $itemTotalTax), 2, '.', ',');

                        $supplierLoopData[$supplierindex]['delivery_total'] = number_format(($total + $itemTotalTax + (isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0) ), 2, '.', ',');

                         $supplierLoopData[$supplierindex]['pickup_charge'] = number_format(($total + $itemTotalTax), 2, '.', ',');
                                 $supplierLoopData[$supplierindex]['pickup_total'] = number_format(($total + $itemTotalTax), 2, '.', ',');
                          

                  }
                 }
                }  
              } 

    
          if(count($supplierLoopData) > 0)
         {
            $result['status'] ="true";
            $result['data'] = $supplierLoopData;
         }
         else
         {
            $result['status'] ="false";
            $result['data'] = [];
         }
       
        return $result;
   }



    /*Get Cart product data */
     public function applyPromocode($data)
    {

        $supplierLoopData = [];
        $offerAmount = 0;  
        $offer_flag = 0;
        $listr ='';
        $offerModel  = New OfferDeals;
        $codeModel  = New OffercodeUsedby; 

        $user_id = $data['user_uuid'];
        $supplierId =  $data['supplier_uuid'];
        $promocode =  $data['promocode'];

        $todayDate = Carbon::now()->format('Y-m-d'); 
        $offers = $offerModel->where("user_id", $supplierId)->where("offercode", $promocode)->where("status","active")->whereDate('end_date' , '>=', $todayDate)->first();

        if($offers)
        {
          $is_useBy = $codeModel->where('user_id', $user_id)->where('offer_id',  $offers->uuid )->get();
            if(count($is_useBy) > 0){
                $offer_flag = 1;
            } else {
                $offer_flag = 3;
            }
        }
        
        $arrOffersSupplier = $offerModel->where("user_id", $supplierId)->whereDate('end_date' , '>=', $todayDate)->pluck("offercode")->toArray();

        if (count($arrOffersSupplier)) {
          $listr='Promocode available for you : ';
          $length = strlen($listr);
          foreach ($arrOffersSupplier as $offerCode) {
              if ($offerCode != $promocode) {
                  $listr .= $offerCode .', ';
              } 
          }
          if ($length < strlen($listr)) {
              $listr = substr($listr, 0, strlen($listr)-2);
          } else {
              $listr .= 'None';
          }
            
        }


        if($offer_flag == 3 ) {


              $basketModel = New Basket;
              $supplierItemInventoryModel = New SupplierItemInventory;
              $userModel = New User;
              $logisticModel = New LogisticDetails; 
              $deliveryVehicleMasterModel = New DeliveryVehicleMaster; 
              
              $currentUser = $userModel->where('uuid',$user_id)->first();
              
              $dWeight ="";
              $basket_data = $basketModel->getBasketforAPI($user_id);

              if($basket_data->first())
                {
                    //Get backetid and get product of cart
                    $basketId = $basket_data['0'];    
                    $basket = $basketModel->where('uuid', $basketId)->first();
                    $basketProducts = $basket->products;
                    $arrBasketProducts = array();
                    foreach($basketProducts as $productKey => $productData){
                        if (!empty($productData['single_qty']) && $productData['single_qty'] >=0){
                            $arrBasketProducts[] = $productData;
                            $basketProductIds[] = $productData['product_id'];
                        }
                    } 
                  
                  //dd(DB::getQueryLog()); // Show results of log
                  $supplierLoopData = [];
                   $supplierLoopData['status'] ="true";
                  $suppliers = $userModel->where('uuid', $supplierId)->with('company')->get();

                 

                  foreach($suppliers as $supplierindex => $supplier){
                    if(!empty($supplier->latitude) && !empty($supplier->longitude)){

                       
                $supplierLoopData["supplier_display"] = $supplier->company()->exists() ? $supplier->company->trading_name : $supplier->name;
                $supplierLoopData["supplier_uuid"] = $supplier->uuid;
                if($supplier->image !=''){
                    $supplierLoopData["supplier_image"] = url('/').$supplier->image;
                }else{
                    $supplierLoopData["supplier_image"] ="";
                }
                //$supplierLoopData[$supplierindex]["products"] = [];

                //$supplierLoopData[$supplierindex]["product_total_weight_unit"] = [];
               // $supplierLoopData[$supplierindex]["total"] = [];
                //$supplierLoopData[$supplierindex]["delivery_type"] = [];

                $totalWeightUnit =  [];
                $total = 0;
                $totalWeight = 0;
                $totalProducts = 0;
                $totalAvailableProducts = 0;
                $itemTotalTax = 0;
                $distanceValue = 0;
                 foreach($arrBasketProducts as $proIndex => $basketProduct)
                {
                    $totalProducts++;
                    $rowTotal = 0;
                    if(!empty($basketProduct->single_qty) && $basketProduct->single_qty > 0)
                    {
                        $supplierItemInventoryDataModel = $supplierItemInventoryModel->where('product_id', $basketProduct->product_id)->where('user_id', $supplier->uuid);
                        if ($basketProduct->single_qty > 0) {
                            $supplierItemInventoryModel->where('single', '>', 0);
                        }
                        $supplierItemInventoryDataModel->whereNotNull('single_price')->where('single_price', '>', 0);
                        $supplierLatestRate = $supplierItemInventoryDataModel->first();
                        
                       
                        if($supplierLatestRate && !empty($supplierLatestRate->single_price) && $supplierLatestRate->single_price > 0){
                       
                            $singlePrice = $supplierLatestRate->single_price;
                          
                            $itemWeight = 0;
                            $productitemTax = 0;
                            $totalAvailableProducts++;

                            $productName = $supplierLatestRate->product->name;
                            $supplierProductLoopData["product_name"] = $productName;
                            $supplierProductLoopData["stock"] = [];
                            if($basketProduct->single_qty > 0) {
                                $supplierProductLoopData["stock"]["single"]["qty"] = $basketProduct->single_qty;
                                $supplierProductLoopData["stock"]["single"]["price"] = $singlePrice;
                                
                                $itemWeight = $basketProduct->product->getCalculatedWeight("single", $basketProduct->single_qty);
                                $rowTotal = ($basketProduct->single_qty * $singlePrice);
                                $productitemTax = $basketProduct->product->getCalculatedTax("single", $basketProduct->single_qty, $singlePrice);

                                $supplierProductLoopData["stock"]["single"]["row_total_price"] = $rowTotal;
                                $supplierProductLoopData["stock"]["single"]["weight"] = $itemWeight;
                                $supplierProductLoopData["stock"]["single"]["tax"] = $productitemTax;
                                
                            }
                          
                            $totalWeight += $itemWeight;
                            $itemTotalTax += $productitemTax;
                            $total += $rowTotal;
                            
                            // $supplierLoopData[$supplierindex]["products"][$proIndex] = $supplierProductLoopData;

                        }    
                    }   
                }


                           
                    
                    //$supplierLoopData[$supplierindex]["total_product_weight"] = $totalWeight;
                   

                    $supplierLoopData["product_price"] = $total;
                    $supplierLoopData["total_tax"] = $itemTotalTax;


                    $totalWeightUnit = $supplier->kgToUnit($totalWeight);
                    
                     $supplierLoopData["total_weight"] = $totalWeight .' '. $totalWeightUnit["unit"];
                    //$supplierLoopData[$supplierindex]["weight"] = $totalWeightUnit["weight"];

                  //  $supplierLoopData[$supplierindex]["unit"] = $totalWeightUnit["unit"];

                    $supplierLoopData["total_available_products"] = $totalAvailableProducts;
                    $availablePercentage = intval((100 * $totalAvailableProducts) / $totalProducts);
                    $supplierLoopData["available_percentage_product"] = $availablePercentage.'% Products Available';  


                    
                    if(($totalWeightUnit["unit"] == 'ton' && $totalWeightUnit["weight"] < 1 ) || $totalWeightUnit["unit"] != 'ton') 
                    {

                     
                      $supplierLoopData["message_ton"] ="Please do order Min 1 Ton , Continue more shopping or Are you continue to  paying for 1 Ton Delivery Charge."; 
                      $dWeight = 1000;
                    }
                    else
                    {
                      $dWeight =$totalWeightUnit["weight"] * 1000;
                    }
                  
                    $distance = $supplier->getDrivingDistance($currentUser->latitude, $currentUser->longitude, $supplier->latitude, $supplier->longitude);  
                    $distanceValue = isset($distance['distance']) ? $distance['distance'] : 0;


                    $deliveryDetails = $deliveryVehicleMasterModel->getDeliveryPrice($dWeight, $distanceValue);

                     $available_list = $logisticModel->getVehicle($dWeight);
                      $vehicle_list= array();
                      foreach ($available_list as $key1 => $value1){

                              $vehicle_list[] = $value1->vehicle_type;
                      }

                        $offer_uuid = '';
                        if($offers)
                            {
                              $offer_uuid = $offers->uuid;
                               $total_amount = $total + $itemTotalTax;
                               $is_useBy = $codeModel->where('user_id', $user_id)->where('offer_id',  $offers->uuid )->get();
                                if(count($is_useBy) > 0){
                                    //$offer_flag = 1;
                                } else {
                                    //$offer_flag = $offers->uuid;
                                    if($offers->offer_type == 'RENT') {
                                        $offerAmount = $offers->offer_value;
                                    } else {
                                        $offerAmount = $total_amount * $offers->offer_value/100;
                                    }
                                }
                            }  


                 


                     //$supplierLoopData[$supplierindex]["delivery_type"] = [] ;
                     //$supplierLoopData[$supplierindex]["delivery_type"]['pickup'] = [];
                  
                    // $supplierLoopData[$supplierindex]["delivery_type"]['delivery'] = [];
                     $supplierLoopData['total_distance'] = $distanceValue;
                   

                     $supplierLoopData['delivery_vehicle'] = implode(' OR ', array_unique($vehicle_list));

                      $supplierLoopData['delivery_descrition'] = implode(' OR ', array_unique($vehicle_list));

                      $supplierLoopData['approximate_pallet_capacity'] =isset($deliveryDetails['palletCapacity']) ? $deliveryDetails['palletCapacity'] : null;

                   
                     $supplierLoopData['delivery_charge'] =isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0;

                   
                    $supplierLoopData['offer_amount'] = $offerAmount;
                    $supplierLoopData['offer_uuid'] = $offer_uuid;
                      
                   
                   $supplierLoopData['product_charge'] = number_format(($total + $itemTotalTax - $offerAmount ), 2, '.', ',');

                    $supplierLoopData['delivery_total'] = number_format(($total + $itemTotalTax + (isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0) ) - $offerAmount  , 2, '.', ',');

                  
                   $supplierLoopData['pickup_charge'] = number_format(($total + $itemTotalTax-$offerAmount), 2, '.', ',');
                    
                    $supplierLoopData['pickup_total'] = number_format(($total + $itemTotalTax-$offerAmount), 2, '.', ',');
                      
                    }
                  }  
                } 
         } 



        //send responce 
        if($offer_flag == 0){
          $result['status'] ="false";
          $result['message'] ="Promo Code is not valid.".$listr;
        
        }elseif ($offer_flag == 1) {

          $result['status'] ="false";
           $result['message'] ="Promo Code is already used." .$listr; 
          
        }else{  
          
           if(count($supplierLoopData) > 0)
           {
              
              $result = $supplierLoopData;
           }
           else
           {
              $result['status'] ="false";
             
           }
         
         
         }
        return $result;  
   }

    public function getWallet($data)
    {

       $transactionsModel = new WalletTransactions; 
       $users = New User;
       $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;
       $limit = isset($data['limit']) ? $data['limit'] : 10; 
       $user_id = $data['user_uuid']; 

       $userdata = $users->where('uuid',$user_id)->first();
       $transactions = $transactionsModel->where('user_id', $user_id)->orderBy('id', 'DESC')->get()->toArray();

         if(count($transactions) > 0){
            $result['status'] ="true";
            $result['wallet_balance'] = $userdata->wallet_balance;
            $result['data'] = $transactions;
         } else {
           $result['status'] ="false";
            $result['wallet_balance'] = 0;
           $result['data'] = [];
         }
              
        return $result;
   } 

   public function addWallet($data)
   { 
     $data = array_merge($data,array('status'=>'PENDING'));
     $user_data = User::where('uuid',$data['user_id'])->get()->first(); 
     
        /*$data_arr['subject'] = 'Add money in wallet';
        $data_arr['first_name'] = $user_data->first_name;
        $data_arr['last_name'] = $user_data->last_name;
        $data_arr['amount'] = $data['credit_amount'];
*/
      /*  $user_email = "himanshu.terbivista@gmail.com";  
        Mail::send([], $data, function($message) use ($data_arr) {
            $message->to($user_email)->subject
               ($data_arr['subject']) 
               ->setBody('Hello Admin, Trader :  '.$data_arr['first_name'].' '.$data_arr['last_name'].' credit R '.$data_arr['amount'].' in wallet. Please review and approve.Regards 
               Itradezon');
            $message->from('info@itradezon.com','Itradezon');
         });*/

    return WalletTransactions::create($data);
     
   }
 

   public function getTransactionType(){
       
       //$data = WalletTransactions::getTransactionTypeDropDown();

        $walletTransactions = new WalletTransactions;

        $transactions_type = $walletTransactions->transactionType;
        $result =array();
        $result['status'] ="true";
        $result['data'] = $transactions_type;
          
        return $result;
    }

        /*Get Cart product data */
     public function orderDetails($data)
    {

      
      //  $supplierLoopData = [];
        $basketModel = New Basket;
        $supplierItemInventoryModel = New SupplierItemInventory;
        $userModel = New User;
        $logisticModel = New LogisticDetails; 
        $deliveryVehicleMasterModel = New DeliveryVehicleMaster; 
        $user_id = $data['user_uuid'];
        $supplier_id = $data['supplier_uuid'];
        

        $currentUser = $userModel->where('uuid',$user_id)->first();
        
        $dWeight ="";
        $basket_data = $basketModel->getBasketforAPI($user_id);
        if($basket_data->first())
          {
              //Get backetid and get product of cart
              $basketId = $basket_data['0'];    
              $basket = $basketModel->where('uuid', $basketId)->first();
              $basketProducts = $basket->products;
              $arrBasketProducts = array();
              foreach($basketProducts as $productKey => $productData){
                  if (!empty($productData['single_qty']) && $productData['single_qty'] >=0){
                      $arrBasketProducts[] = $productData;
                      $basketProductIds[] = $productData['product_id'];
                  }
              } 
            
            $supplierIdsWithStockModal = $supplierItemInventoryModel->whereIn('product_id', $basketProductIds);
            $supplierIdsWithStockModal->where(function($q) {
                $q->whereNotNull('single');
                $q->where('single', '>', 0);
                $q->whereNotNull('single_price');
                $q->where('single_price', '>', 0);
            });
            
            //dd(DB::getQueryLog()); // Show results of log
            $supplierLoopData = [];
            $suppliers = $userModel->where('uuid', $supplier_id)->with('company')->get();
          foreach($suppliers as $supplierindex => $supplier){
              if(!empty($supplier->latitude) && !empty($supplier->longitude)){

                $supplierLoopData['status'] = "true";  
               // $supplierLoopData[$supplierindex]["products"] = [];

                $totalWeightUnit =  [];
                $total = 0;
                $totalWeight = 0;
                $totalProducts = 0;
                $totalAvailableProducts = 0;
                $itemTotalTax = 0;
                $distanceValue = 0;
                 foreach($arrBasketProducts as $proIndex => $basketProduct)
                {
                    $totalProducts++;
                    $rowTotal = 0;
                    if(!empty($basketProduct->single_qty) && $basketProduct->single_qty > 0)
                    {
                        $supplierItemInventoryDataModel = $supplierItemInventoryModel->where('product_id', $basketProduct->product_id)->where('user_id', $supplier->uuid);
                        if ($basketProduct->single_qty > 0) {
                            $supplierItemInventoryModel->where('single', '>', 0);
                        }
                        $supplierItemInventoryDataModel->whereNotNull('single_price')->where('single_price', '>', 0);
                        $supplierLatestRate = $supplierItemInventoryDataModel->first();
                        

                        if($supplierLatestRate && !empty($supplierLatestRate->single_price) && $supplierLatestRate->single_price > 0){
                       
                            $singlePrice = $supplierLatestRate->single_price;
                            $itemWeight = 0;
                            $productitemTax = 0;
                            $totalAvailableProducts++;

                            $productName = $supplierLatestRate->product->name;
                            $supplierQty = $supplierLatestRate->single;

                            $supplierProductLoopData["product_name"] = $productName;
                            
                       

                            if($supplierLatestRate->product->base_image != '')
                            { 
                               
                               $supplierProductLoopData["product_image"] = url('/'). $supplierLatestRate->product->base_image;
                            }
                            else
                            { 
                              $supplierProductLoopData["product_image"] = '';
                            }


                            if($basketProduct->single_qty > 0) {

                                  if($supplierQty >= $basketProduct->single_qty) {

                                      $supplierProductLoopData["stock_type"] = $supplierLatestRate->product->stock_type;
                                       $supplierProductLoopData["product_qty"] = $basketProduct->single_qty;
                                       $supplierProductLoopData["available_stock"] = "";
                                  }else{
                                      $supplierProductLoopData["stock_type"] = $supplierLatestRate->product->stock_type;
                                      $supplierProductLoopData["product_qty"] = $supplierQty;
                                      $supplierProductLoopData["available_stock"] = "AVAILABEL STOCK";
                                  }

                                $supplierProductLoopData["product_price"] = $singlePrice;
                                
                                $itemWeight = $basketProduct->product->getCalculatedWeight("single", $basketProduct->single_qty);
                                $rowTotal = ($basketProduct->single_qty * $singlePrice);
                                $productitemTax = $basketProduct->product->getCalculatedTax("single", $basketProduct->single_qty, $singlePrice);

                                $supplierProductLoopData["row_total_price"] = $rowTotal;
                                $supplierProductLoopData["product_weight"] = $itemWeight;
                                $supplierProductLoopData["product_tax"] = $productitemTax;
                                
                            }
                          
                            $totalWeight += $itemWeight;
                            $itemTotalTax += $productitemTax;
                            $total += $rowTotal;
                            
                             $supplierLoopData["products"][$proIndex] = $supplierProductLoopData;

                        }    
                    }   
                }

                    $totalWeightUnit = $supplier->kgToUnit($totalWeight);
                            
                    
                    //$supplierLoopData[$supplierindex]["total_product_weight"] = $totalWeight;
                   

                    if(($totalWeightUnit["unit"] == 'ton' && $totalWeightUnit["weight"] < 1 ) || $totalWeightUnit["unit"] != 'ton') 
                    {
                    /* $supplierLoopData[$supplierindex]["message_ton"] ="Please do order Min 1 Ton , Continue more shopping or Are you continue to  paying for 1 Ton Delivery Charge."; 
                    */  $dWeight = 1000;
                    }
                    else
                    {
                      $dWeight =$totalWeightUnit["weight"] * 1000;
                    }
                  
                    $distance = $supplier->getDrivingDistance($currentUser->latitude, $currentUser->longitude, $supplier->latitude, $supplier->longitude);  
                    $distanceValue = isset($distance['distance']) ? $distance['distance'] : 0;

                    $deliveryDetails = $deliveryVehicleMasterModel->getDeliveryPrice($dWeight, $distanceValue);
                    $supplierLoopData["total_price"] = $total;
                   
                    $supplierLoopData["total_tax"] = $itemTotalTax;
                    $supplierLoopData["total_discount"] = 0;

                     $supplierLoopData["delivery_charge"] = isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0;



                    $supplierLoopData['pickup_total'] = number_format(($total + $itemTotalTax), 2, '.', ',');                   
                
                    $supplierLoopData['delivery_total'] = number_format(($total + $itemTotalTax + (isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0) ), 2, '.', ',');
  
                     $supplierLoopData["total_weight"] = $totalWeight .' '. $totalWeightUnit["unit"];

                   
              }
            }  
          } 


          if(count($supplierLoopData) > 0)
         {
            $result =  $supplierLoopData;
          }
         else
         {
           
            $result = ["status" => "false"];
         }
       
        return $result;
   }


       /*Get Cart product data */
     public function paymentSummary($data)
    {
      //  $supplierLoopData = [];
        $basketModel = New Basket;
        $supplierItemInventoryModel = New SupplierItemInventory;
        $userModel = New User;
        $logisticModel = New LogisticDetails; 
        $deliveryVehicleMasterModel = New DeliveryVehicleMaster; 
        $user_id = $data['user_uuid'];
        $supplier_id = $data['supplier_uuid'];
        $delivery_method = $data['delivery_method'];
        

        $currentUser = $userModel->where('uuid',$user_id)->first();
        
        $dWeight ="";
        $basket_data = $basketModel->getBasketforAPI($user_id);
        if($basket_data->first())
          {
              //Get backetid and get product of cart
              $basketId = $basket_data['0'];    
              $basket = $basketModel->where('uuid', $basketId)->first();
              $basketProducts = $basket->products;
              $arrBasketProducts = array();
              foreach($basketProducts as $productKey => $productData){
                  if (!empty($productData['single_qty']) && $productData['single_qty'] >=0){
                      $arrBasketProducts[] = $productData;
                      $basketProductIds[] = $productData['product_id'];
                  }
              } 
            
            $supplierIdsWithStockModal = $supplierItemInventoryModel->whereIn('product_id', $basketProductIds);
            $supplierIdsWithStockModal->where(function($q) {
                $q->whereNotNull('single');
                $q->where('single', '>', 0);
                $q->whereNotNull('single_price');
                $q->where('single_price', '>', 0);
            });
            
            //dd(DB::getQueryLog()); // Show results of log
            $supplierLoopData = [];
            $suppliers = $userModel->where('uuid', $supplier_id)->with('company')->get();
            foreach($suppliers as $supplierindex => $supplier){
              if(!empty($supplier->latitude) && !empty($supplier->longitude)){


                  

                $supplierLoopData['status'] = "true";  

                $supplierLoopData["supplier_name"] = $supplier->name;
                $supplierLoopData["supplier_company"] = $supplier->company()->exists() ? $supplier->company->trading_name : $supplier->name;
                // $supplierLoopData["supplier_company_name"] = $supplier->companyname;
               
               
                 $supplierLoopData["supplier_company_email"] = $supplier->email;
                 $supplierLoopData["supplier_company_address"] = $supplier->company()->exists() ? $supplier->company->address : null;
                 $supplierLoopData["supplier_company_id"] = $supplier->company()->exists() ? $supplier->company->uuid : null;
               
               // $supplierLoopData[$supplierindex]["products"] = [];

                $totalWeightUnit =  [];
                $total = 0;
                $totalWeight = 0;
                $totalProducts = 0;
                $totalAvailableProducts = 0;
                $itemTotalTax = 0;
                $distanceValue = 0;
                 foreach($arrBasketProducts as $proIndex => $basketProduct)
                {
                    $totalProducts++;
                    $rowTotal = 0;
                    if(!empty($basketProduct->single_qty) && $basketProduct->single_qty > 0)
                    {
                        $supplierItemInventoryDataModel = $supplierItemInventoryModel->where('product_id', $basketProduct->product_id)->where('user_id', $supplier->uuid);
                        if ($basketProduct->single_qty > 0) {
                            $supplierItemInventoryModel->where('single', '>', 0);
                        }
                        $supplierItemInventoryDataModel->whereNotNull('single_price')->where('single_price', '>', 0);
                        $supplierLatestRate = $supplierItemInventoryDataModel->first();
                        
                       
                        if($supplierLatestRate && !empty($supplierLatestRate->single_price) && $supplierLatestRate->single_price > 0){
                       
                            $singlePrice = $supplierLatestRate->single_price;
                          
                            $itemWeight = 0;
                            $productitemTax = 0;
                            $totalAvailableProducts++;

                            $productName = $supplierLatestRate->product->name;
                            $supplierQty = $supplierLatestRate->single;

                            $supplierProductLoopData["product_name"] = $productName;
                           

                            if($supplierLatestRate->product->base_image != '')
                            {
                              $supplierProductLoopData["product_image"] = url('/'). $productName;
                            }
                            else
                            { 
                              $supplierProductLoopData["product_image"] = '';
                            }


                            if($basketProduct->single_qty > 0) {

                                  if($supplierQty >= $basketProduct->single_qty) {

                                      $supplierProductLoopData["stock_type"] = $supplierLatestRate->product->stock_type;
                                       $supplierProductLoopData["product_qty"] = $basketProduct->single_qty;
                                       $supplierProductLoopData["available_stock"] = "";
                                  }else{
                                      $supplierProductLoopData["stock_type"] = $supplierLatestRate->product->stock_type;
                                      $supplierProductLoopData["product_qty"] = $supplierQty;
                                      $supplierProductLoopData["available_stock"] = "AVAILABEL STOCK";
                                  }

                                $supplierProductLoopData["product_price"] = $singlePrice;
                                
                                $itemWeight = $basketProduct->product->getCalculatedWeight("single", $basketProduct->single_qty);
                                $rowTotal = ($basketProduct->single_qty * $singlePrice);
                                $productitemTax = $basketProduct->product->getCalculatedTax("single", $basketProduct->single_qty, $singlePrice);

                                $supplierProductLoopData["row_total_price"] = $rowTotal;
                                $supplierProductLoopData["product_weight"] = $itemWeight;
                                $supplierProductLoopData["product_tax"] = $productitemTax;
                                
                            }
                          
                            $totalWeight += $itemWeight;
                            $itemTotalTax += $productitemTax;
                            $total += $rowTotal;
                            
                             $supplierLoopData["products"][$proIndex] = $supplierProductLoopData;

                        }    
                    }   
                }

                    $totalWeightUnit = $supplier->kgToUnit($totalWeight);
                            
                    
                    //$supplierLoopData[$supplierindex]["total_product_weight"] = $totalWeight;
                   

                    if(($totalWeightUnit["unit"] == 'ton' && $totalWeightUnit["weight"] < 1 ) || $totalWeightUnit["unit"] != 'ton') 
                    {
                    /* $supplierLoopData[$supplierindex]["message_ton"] ="Please do order Min 1 Ton , Continue more shopping or Are you continue to  paying for 1 Ton Delivery Charge."; 
                    */  $dWeight = 1000;
                    }
                    else
                    {
                      $dWeight =$totalWeightUnit["weight"] * 1000;
                    }
                  
                    $distance = $supplier->getDrivingDistance($currentUser->latitude, $currentUser->longitude, $supplier->latitude, $supplier->longitude);  
                    $distanceValue = isset($distance['distance']) ? $distance['distance'] : 0;

                    $deliveryDetails = $deliveryVehicleMasterModel->getDeliveryPrice($dWeight, $distanceValue);
                    
                    $supplierLoopData["delivery_method"] = $delivery_method;

                    $supplierLoopData["total_price"] = $total;
                   
                    $supplierLoopData["total_tax"] = $itemTotalTax;
                    $supplierLoopData["total_discount"] = 0;


                    
                    if($delivery_method =='pickup'){

                       $supplierLoopData["delivery_charge"] = 0;

                       $supplierLoopData['payable_amount'] = number_format(($total + $itemTotalTax), 2, '.', ',');                   
                    }else{

                       $supplierLoopData["delivery_charge"] = isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0;


                       $supplierLoopData['payable_amount'] = number_format(($total + $itemTotalTax + (isset($deliveryDetails['price']) ? $deliveryDetails['price'] : 0) ), 2, '.', ',');
                    }

                     $supplierLoopData["total_weight"] = $totalWeight .' '. $totalWeightUnit["unit"];
                     $supplierLoopData["wallet_balance"] = $currentUser->wallet_balance; 
                    
                   
              }
            }  
          } 



          if(count($supplierLoopData) > 0)
         {
            if($supplierLoopData['payable_amount'] >   $supplierLoopData["wallet_balance"] )
               {
                   $result = ["status" => "false"];
                   $result["message"] = 'Insufficient wallet amount. Please add amount to your wallet and complete payment.'; 

               }else{

                  $result =  $supplierLoopData;

               }  
           
          }
         else
         {
           
            $result = ["status" => "false"];
         }
       
        return $result;
   }



   public function orderPlaced($data)
   {

    $user_id = $data['user_uuid'];
    $supplier_uuid = $data['supplier_uuid'];
    $shippingMethod = $data['delivery_method'];
    $cartAmount = $data['cart_amount'];
    $offer_id = isset($data['offer_id']) ? $data['offer_id'] : 0;
    $tax_amount = $data['tax_amount'];
    $offer_amount = $data['offer_amount'];
    $shipment_amount = $data['shipment_amount'];
    $final_total = $data['final_total'];

    $basketModel = New Basket;
    $salesOrder  = new SalesOrder;
    $codeModel  = New OffercodeUsedby; 
    $inventary = new SupplierItemInventory; 
    $walletTransactionModel = new WalletTransactions; 


    $basket_data = $basketModel->getBasketforAPI($user_id);
    
      if($basket_data->first())
        {
           $basketId = $basket_data['0'];   
           $basket = $basketModel->where('uuid', $basketId)->first();
            $products = $basket->products;
            foreach ($products as $pro) {
                $inventary->where('product_id', $pro->product_id)->where('user_id', $supplier_uuid)->decrement('single', $pro->single_qty);
            }
            

            /***Wallet Entry***/
            $walletTransactionModel->create([
                "credit_amount" => 0,
                "debit_amount" => $final_total,
                "user_id" => $user_id,
                "remarks" => "BUY PRODUCT",
                "status" => "APPROVED"
                ]);

             $walletTransactionModel->create([
                "credit_amount" => $final_total,
                "debit_amount" => 0,
                "user_id" => $supplier_uuid,
                "remarks" => "SELL PRODUCT",
                "status" => "APPROVED" 
                ]);

             /***Sales Entry***/
            $salesOrderData = $salesOrder->create([
                'user_id' => $user_id,
                'supplier_id' => $supplier_uuid,
    //           'logistic_id',
                'cart_amount' => $cartAmount,
                'shipment_amount' => $shipment_amount,
                'discount_amount' => $offer_amount,
                'tax_amount' => $tax_amount,
                'final_total' => $final_total,
                'order_status' => SalesOrder::ORDERPLACED,
                // 'order_status' => "PLACED",
                'payment_status' => "COMPLETED",
                'delivery_type' => $shippingMethod,
                
            ]);
            

            $req_data = array();
           // $req_data = array();
            /***********************Notification to supplier when order placed ************************/ 
            $req_data['order_status'] = SalesOrder::ORDERPLACED;
            // $req_data['order_status'] = "PLACED";
            $req_data['order_uuid'] = $salesOrderData->uuid;  
            $req_data['user_id'] = $supplier_uuid;
            $req_data['delivery_type'] = $shippingMethod;

            ChangeOrderStatus::orderStatus($req_data); //notify supplier 

            /***********************Notification to supplier Close ************************/ 

        /***Offer User BY this user Entry***/
        if($offer_id != 0){
            $codeModel->create([
                'user_id' =>  $user_id, 
                'offer_id' => $offer_id , 
                'order_id' =>$salesOrderData->uuid 
            ]);  
        }


        $basket = $basketModel->where("uuid", $basketId)->update(["order_id" => $salesOrderData->uuid]);

        return $salesOrderData->order_number;

      }


   }

   public function orderlist($data)
    {
     
      $user_uuid = isset($data['user_uuid']) ? $data['user_uuid'] : '';
      $order_uuid = isset($data['order_uuid']) ? $data['order_uuid'] : '';
       
       if($user_uuid != ''){

         $salesOrder  = new SalesOrder;
         $orders = $salesOrder->where('order_status','PACKED')->where('delivery_type','delivery')->where('uuid',$order_uuid)->first();    
       
        $result = array();
        $data_order = array();
       
        if($orders){
        $data_order['order_number'] = $orders->order_number;
        $data_order['order_uuid'] = $orders->uuid;
        $data_order['supplier_uuid'] = $orders->supplier_id;
        $data_order['supplier_name'] = $orders->supplier->name;
        $data_order['vendor_uuid'] = $orders->user_id;
        $data_order['vendor_name'] = $orders->user->name;
        $data_order['order_amount'] = $orders->final_total;
        $data_order['order_date'] = $orders->created_at->format('d/m/Y h:i:s');
        $data_order['pickup_latitude'] =$orders->supplier->latitude;
        $data_order['pickup_longitude'] = $orders->supplier->longitude;
        $data_order['deliver_latitude'] = $orders->user->latitude;
        $data_order['deliver_longitude'] = $orders->user->longitude;
       
       if($orders->logistic_id !=''){
        $data_order['driver_uuid'] = $orders->logistic_id;
        $data_order['driver_name'] = $orders->logistic->name;
        
        $data_order['driver_longitude'] = $orders->logistic->latitude;
        $data_order['driver_latitude'] = $orders->logistic->longitude;
        $data_order['vehicle_type'] = $orders->logisticDetails->vehicle_type;
        $data_order['driving_licence'] = $orders->logisticDetails->driving_licence;

        $data_order['transport_type'] = $orders->logisticDetails->transport_type;
        $data_order['transport_capacity'] = $orders->logisticDetails->transport_capacity;
         $data_order['transport_type'] = $orders->logisticDetails->transport_type;
        $data_order['transport_capacity'] = $orders->logisticDetails->transport_capacity;

        $data_order['pallet_capacity_standard'] = $orders->logisticDetails->pallet_capacity_standard;
        $data_order['work_type'] = $orders->logisticDetails->work_type;
  
        $data_order['address'] = $orders->logisticDetails->address;

       }

        $data_order['vendor_updated_longitude'] = $orders->vendor_updated_longitude ? $orders->vendor_updated_longitude :$orders->user->longitude ;  
        $data_order['vendor_updated_latitude'] = $orders->vendor_updated_latitude ? $orders->vendor_updated_latitude : $orders->user->latitude;
        $data_order['supplier_updated_longitude'] = $orders->supplier_updated_longitude ? $orders->supplier_updated_longitude  : $orders->supplier->longitude;
        $data_order['supplier_updated_latitude'] = $orders->supplier_updated_latitude ?  $orders->supplier_updated_latitude : $orders->supplier->latitude;
        if($orders->logistic_id !=''){
         $data_order['driver_updated_longitude'] = $orders->driver_updated_longitude ?  $orders->driver_updated_longitude  : $orders->logistic->longitude ;
           $data_order['driver_updated_latitude'] = $orders->driver_updated_latitude ?  $orders->driver_updated_latitude : $orders->logistic->latitude;
        }
      }

        if(count($data_order)){ 
            $result['status'] ="true"; 
            $result['message'] ="Got Data";
            $result['orders'] = $data_order;
         } else {  
            $result['status'] ="false";
            $result['orders'] = [];
         }
              
        return $result;
      }
   }
  /**
   * Purpose Accounts api for users
   */
  public function accountsList($data)
  {

      $user_uuid = isset($data['user_uuid']) ? $data['user_uuid'] : '';
      $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;
      $limit = isset($data['limit']) ? $data['limit'] : 10;
      $data = array(); 
      $orderTotal = 0;
      $orders = array();
      if ($user_uuid != '' &&  User::where('uuid', $user_uuid)->count()>0) {
        
        $arrRole = User::where('uuid', $user_uuid)->select('role')->first(); 
       
      

         $role = $arrRole->role;
         $salesOrder  = new SalesOrder;
          
        if ($role == 'SUPPLIER') {
          $orderTotal = $salesOrder->where('supplier_id', $user_uuid)->where('order_status','!=' ,'CANCELLED')->sum('final_total');
          $orders = $salesOrder->where('supplier_id',$user_uuid)->skip($offset)->take($limit)->orderBy('created_at','desc')->get();
        } else if ($role == 'VENDOR') {
          $orderTotal = $salesOrder->where('user_id', $user_uuid)->where('order_status','!=' ,'CANCELLED')->sum('final_total');
          $orders = $salesOrder->where('user_id', $user_uuid)->skip($offset)->take($limit)->orderBy('created_at','desc')->get();
          //$slimmed_down = $orders->toArray()->only(['uuid']);         
        } else if ($role == 'DRIVER' || $role == 'COMPANY') {
          $orderTotal = $salesOrder->where('logistic_id', $user_uuid)->where('order_status','!=' ,'CANCELLED')->sum('final_total');
          $orders = $salesOrder->where('logistic_id', $user_uuid)->skip($offset)->take($limit)->orderBy('created_at','desc')->get();
        } 
      } 
          
      if(count($orders) > 0){
        $data = collect($orders)->map(function($item){
          $tabstatus = 'Pending';
          if($item->order_status == 'DELIVERED')
            $tabstatus = 'Completed';
          elseif ($item->order_status == 'CANCELLED') {
            $tabstatus = 'Cancelled';
          }
          else
          {
            $tabstatus = 'Pending';
          }  
          return [ 'order_number' => $item->order_number ,'order_id'=>$item->uuid, 'supplier'=>$item->supplier_name, 
          'delivered' => ($item->order_status == 'DELIVERED') ? 'Completed': 'pending',
          'order_date'=> $item->created_at->format('d M Y'),
          'order_amount'=> $item->final_total, 
          'order_stats' => $item->order_status,
          'delivery_type' => $item->delivery_type,
          ]; })->toArray();
          
        $result['status'] ="true";
        $result['Tab_status'] = $tabstatus;        
        $result['order_total'] = $orderTotal;
        $result['data'] = $data;
      } else {
        $result['status'] ="false";
        $result['order_total'] = $orderTotal;
        $result['data'] = [];
      }
              
        return $result;
   }
   /**
   * Purpose ORDER LisT FOR tracking api for users
   */
  public function orderlistTrack($data)
  {

      $user_uuid = isset($data['user_uuid']) ? $data['user_uuid'] : '';
      $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;
      $limit = isset($data['limit']) ? $data['limit'] : 10;
      $data = array(); 
      $orderTotal = 0;
      $orders = array();
      if ($user_uuid != '' &&  User::where('uuid', $user_uuid)->count()>0) {
        
        $arrRole = User::where('uuid', $user_uuid)->select('role')->first(); 
            
         $role = $arrRole->role;
         $salesOrder  = new SalesOrder;
        
        // if ($role == 'SUPPLIER') {
        //   $orderTotal = $salesOrder->where('supplier_id', $user_uuid)->where('order_status','!=' ,'PLACED')->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->sum('final_total');
        //   $orders = $salesOrder->where('supplier_id',$user_uuid)->where('order_status','!=' ,'PLACED')->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->skip($offset)->take($limit)->orderBy('created_at','desc')->get();
        // } else if ($role == 'VENDOR') {
        //   $orderTotal = $salesOrder->where('user_id', $user_uuid)->where('order_status','!=' ,'PLACED')->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->sum('final_total');
        //   $orders = $salesOrder->where('user_id', $user_uuid)->where('order_status','!=' ,'PLACED')->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->skip($offset)->take($limit)->orderBy('created_at','desc')->get();
       
        //   //$slimmed_down = $orders->toArray()->only(['uuid']);         
        // } else if ($role == 'DRIVER' || $role == 'COMPANY') {
        //   $orderTotal = $salesOrder->where('logistic_id', $user_uuid)->where('order_status','!=' ,'PLACED')->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->sum('final_total');
        //   $orders = $salesOrder->where('logistic_id', $user_uuid)->where('order_status','!=' ,'PLACED')->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->skip($offset)->take($limit)->orderBy('created_at','desc')->get();
        // }
        if ($role == 'SUPPLIER') {
          $orderTotal = $salesOrder->where('supplier_id', $user_uuid)->where('order_status','!=' , SalesOrder::ORDERPLACED)->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->sum('final_total');
          $orders = $salesOrder->where('supplier_id',$user_uuid)->where('order_status','!=' , SalesOrder::ORDERPLACED)->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->skip($offset)->take($limit)->orderBy('created_at','desc')->get();
        } else if ($role == 'VENDOR') {
          $orderTotal = $salesOrder->where('user_id', $user_uuid)->where('order_status','!=' , SalesOrder::ORDERPLACED)->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->sum('final_total');
          $orders = $salesOrder->where('user_id', $user_uuid)->where('order_status','!=' , SalesOrder::ORDERPLACED)->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->skip($offset)->take($limit)->orderBy('created_at','desc')->get();
       
          //$slimmed_down = $orders->toArray()->only(['uuid']);         
        } else if ($role == 'DRIVER' || $role == 'COMPANY') {
          $orderTotal = $salesOrder->where('logistic_id', $user_uuid)->where('order_status','!=' , SalesOrder::ORDERPLACED)->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->sum('final_total');
          $orders = $salesOrder->where('logistic_id', $user_uuid)->where('order_status','!=' , SalesOrder::ORDERPLACED)->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->skip($offset)->take($limit)->orderBy('created_at','desc')->get();
        }  
      } 
          
      if(count($orders) > 0){
        $data = collect($orders)->map(function($item){
        

          return ['order_number' => str_pad($item->order_id, 7, "0", STR_PAD_LEFT) ,'order_id'=>$item->uuid, 'supplier'=>$item->supplier_name, 
          'delivered' => ($item->order_status == 'DELIVERED') ? 'Completed': 'pending',
          'order_date'=> $item->created_at->format('d M Y'),
          'order_amount'=> $item->final_total, 
          'order_stats' => $item->order_status,
          'delivery_type' => $item->delivery_type,
          ]; })->toArray();
        $result['status'] ="true";
            
        $result['order_total'] = $orderTotal;
        $result['data'] = $data;
      } else {
        $result['status'] ="false";
        $result['order_total'] = $orderTotal;
        $result['data'] = [];
      }
              
        return $result;
   }

   public function updateLocation($postData)
    {

    
   
      return DB::transaction(function () use ($postData) {
          $salesOrder  = new SalesOrder;
          $order_uuid = isset($postData['order_uuid']) ? $postData['order_uuid'] : '';
          $longitude = $postData['longitude'];
          $latitude = $postData['latitude'];


      if ($order_uuid != '' &&  SalesOrder::where('uuid', $order_uuid)->count()>0) {
          
          if($postData['user_type'] == 'VENDOR'){
                 
                 $salesOrder->where("uuid", $order_uuid)->update(["vendor_updated_longitude" => $longitude, "vendor_updated_latitude" => $latitude]);
          } 
          elseif($postData['user_type'] == 'SUPPLIER'){
               $salesOrder->where("uuid", $order_uuid)->update(["supplier_updated_longitude" => $longitude, "supplier_updated_latitude" => $latitude]);

          }
          elseif ($postData['user_type'] == 'DRIVER') {
              $salesOrder->where("uuid", $order_uuid)->update(["driver_updated_longitude" => $longitude, "driver_updated_latitude" => $latitude]);
          } 
          return $salesOrder;
       } 
        });
        throw new CustomeException("Network error. Please try after some time.",500);
    }
  
   public function driverUpdateLocation($postData)
    {

      return DB::transaction(function () use ($postData) {
          $salesOrder  = new SalesOrder;
          $user_uuid = isset($postData['user_uuid']) ? $postData['user_uuid'] : '';
          $longitude = $postData['longitude'];
          $latitude = $postData['latitude'];

          
      if ($user_uuid != '' &&  SalesOrder::where('logistic_id', $user_uuid)->where('order_status','!=' ,SalesOrder::ORDERPLACED)->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->count()>0) {
        // if ($user_uuid != '' &&  SalesOrder::where('logistic_id', $user_uuid)->where('order_status','!=' ,'PLACED')->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->count()>0) {
          
        if ($postData['user_type'] == 'DRIVER') {
              $salesOrder->where("logistic_id", $user_uuid)->where('order_status','!=' ,SalesOrder::ORDERPLACED)->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->update(["driver_updated_longitude" => $longitude, "driver_updated_latitude" => $latitude]);
              // $salesOrder->where("logistic_id", $user_uuid)->where('order_status','!=' ,'PLACED')->where('order_status','!=' ,'CANCELLED')->where('order_status','!=' ,'DELIVERED')->update(["driver_updated_longitude" => $longitude, "driver_updated_latitude" => $latitude]);
          } 
          return $salesOrder;
       } 
        });
        throw new CustomeException("Network error. Please try after some time.",500);
    }
  
}
