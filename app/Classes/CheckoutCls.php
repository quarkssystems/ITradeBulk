<?php



namespace App\Classes;



use App\Exceptions\CustomeException;

use App\Repositories\CheckoutRepository;

use App\General\General;

use App\General\APIRequestValidate;

use Illuminate\Support\Facades\Auth;

use JWTFactory;

use JWTAuth;

use App\User;



class CheckoutCls

{



    protected $userValidate;



    public function __construct(CheckoutRepository $ckoutobj)

    {

        $this->ckoutobj = $ckoutobj;

        $this->userValidate = new APIRequestValidate();

    }



     public

    function addtocart($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid','product_id','qty'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

        try {

          $res = $this->ckoutobj->addtocart($postData);

            if ($res == 'success') {

                

                $data['status'] = "true";

                $data['response'] = [

                    'message' => "Product added to the cart successfully.",

                ];

                return $data;

            }

            elseif($res == 'error'){

                   

                $data['status'] = "false";

                $data['response'] = [

                    'message' => "We would like to inform you that your KYC is not completed. In order to add order please complete your KYC.",

                ];

                return $data;

                

            }

            else {

                $data['status'] = "false";

                $data['response'] = [

                    'message' => "Please try after sometime.",

                ];

                return $data;





            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }



     

     public

    function cart($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

            return $this->ckoutobj->cart($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }





     public

    function removetocart($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid','product_id'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        



        try {

          $res = $this->ckoutobj->removetocart($postData);

            if ($res != '') {

                $data['status'] = 'true'; 

                $data['response'] = [

                    'message' => "Product removed from cart successfully.",

                ];

                return $data;

            } else {

                $data['status'] = 'true'; 

                $data['response'] = [

                    'message' => "Please try after sometime.",

                ];

                return $data;

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }

    



     public

    function selectSupplier($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

            return $this->ckoutobj->selectSupplier($postData);

        } catch (\Exception $e) {
           
             return General::setResponse('NOT_FOUND', 'Network error. Please try after some time');

           // throw new CustomeException("Network error. Please try after some time.",500);

        }

    }





     public

    function orderDetails($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid','supplier_uuid'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

            return $this->ckoutobj->orderDetails($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }





     public

    function paymentSummary($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid','supplier_uuid','delivery_method'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

            return $this->ckoutobj->paymentSummary($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }





       public

    function applyPromocode($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid','supplier_uuid','promocode'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

        }

        

        try {

          return $res = $this->ckoutobj->applyPromocode($postData);

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }

    }



    public function getWallet($postData)
    {
        $v = $this->userValidate->required($postData, array('user_uuid'));
        if ($v->fails()) {
            $data = General::setResponse('validation_error');
            $data['response'] = [
                'message' => $v->errors()->first(),
            ];
            return $data;
        }
        try {
          return $res = $this->ckoutobj->getWallet($postData);
        } catch (\Exception $e) {
            return General::setResponse('unauthorized', $e->getMessage());
        }
    }


    public function getWithdrawal($postData)
    {
        $v = $this->userValidate->required($postData, array('user_uuid'));
        if ($v->fails()) {
            $data = General::setResponse('validation_error');
            $data['response'] = [
                'message' => $v->errors()->first(),
            ];
            return $data;
        }
        try {
          return $res = $this->ckoutobj->getWithdrawal($postData);
        } catch (\Exception $e) {
            return General::setResponse('unauthorized', $e->getMessage());
        }
    }


     public function addWallet($postData)
    {
        $v = $this->userValidate->required($postData, array('user_id','credit_amount','transaction_type','remarks'));
        if ($v->fails()) {
            $data = General::setResponse('validation_error');
            $data['response'] = [
                'message' => $v->errors()->first(),
            ];
            return $data;
            //return General::setResponse('validation_error', $v->errors()->getMessages());
        }
        try {
            $result = $this->ckoutobj->addWallet($postData);
            $refNo = $result->id;
            // print_r($result['id']);die();

            if ($result != '') {
                $data = General::setResponse('success');

                if(isset($result) && $result['transaction_type'] == 'EFT'){
                    $data['response'] = [
                        'message' => "Please deposit the amount in our bank account and enter your beneficiary number W-$refNo in your reference details. \n\nYou can confirm your payment by sending us proof of payment, or Please give us a day or two to reflect the payment in our bank. \n\nThank You",
                    ]; 
                }else{
                    $data['response'] = [
                        'message' => "Wallet transaction Added successfully.",
                    ]; 
                }

                
                return $data;
            } else {
                return General::setResponse('other_error', "Please try after sometime.");
            }
        } catch (\Exception $e) {
            throw new CustomeException("Network error. Please try after some time.",500);
        }
    }

    public function addWithdrawalRequest($postData)
    {
        $v = $this->userValidate->required($postData, array('user_id','amount','remarks'));
        $walletAmount = auth()->user()->wallet_balance;
        // print_r(auth()->user()->wallet_balance);die();
        if ($v->fails()) {
            $data = General::setResponse('validation_error');
            $data['response'] = [
                'message' => $v->errors()->first(),
            ];
            return $data;
            //return General::setResponse('validation_error', $v->errors()->getMessages());
        }

        if(isset($postData['amount']) && !empty($postData['amount']) && $postData['amount'] <= $walletAmount) {
            try {
                $result = $this->ckoutobj->addWithdrawalRequest($postData);
                if ($result != '') {
                    $data = General::setResponse('success');
                    $data['response'] = [
                        'message' => "Withdrawal Request sent successfully.",
                    ];
                    return $data;
                } else {
                    return General::setResponse('other_error', "Please try after sometime.");
                }
            } catch (\Exception $e) {
                throw new CustomeException("Network error. Please try after some time.",500);
            }
        } else {
            return General::setResponse('other_error', "You have not sufficient amount in your wallet.");
        }
    }



     public

    function getTransactionType()

    {

       

        try {

            return $this->ckoutobj->getTransactionType();

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



      public

    function orderPlaced($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid','supplier_uuid','delivery_method','cart_amount','tax_amount','offer_amount','shipment_amount','final_total'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

              

            $result = $this->ckoutobj->orderPlaced($postData);

            if ($result != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "#".$result ." Order placed successfully.",

                ];

                return $data;



            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }





    public function orderlist($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid','order_uuid'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

              

            $result = $this->ckoutobj->orderlist($postData);

            

            return $result;



        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }

    public function accountsList($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

            

            $result = $this->ckoutobj->accountsList($postData);

            

            return $result;



        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



     public function orderlistTrack($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

            

            $result = $this->ckoutobj->orderlistTrack($postData);

            

            return $result;



        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }

  

     public function updateLocation($postData)

    {



        $v = $this->userValidate->required($postData, array('order_uuid','user_type','longitude','latitude'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }





         try {

            $locUpdate = $this->ckoutobj->updateLocation($postData);

            if ($locUpdate != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "Longitude and Latitude updated successfully.",

                ];

                return $data;

            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            return General::setResponse('unauthorized', $e->getMessage());

        }    



    } 



     public function driverUpdateLocation($postData)

    {



        $v = $this->userValidate->required($postData, array('user_uuid','user_type','longitude','latitude'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }

        try {
            return $this->ckoutobj->driverUpdateLocation($postData);
        } catch (\Exception $e) {
            return General::setResponse('unauthorized', $e->getMessage());
        }



        // try {

        //     $locUpdate = $this->ckoutobj->driverUpdateLocation($postData);

        //     if ($locUpdate != '') {

        //         $data = General::setResponse('success');

        //         $data['response'] = [

        //             'message' => "Longitude and Latitude updated successfully.",

        //         ];

        //         return $data;

        //     } else {

        //         return General::setResponse('other_error', "Please try after sometime.");

        //     }

        // } catch (\Exception $e) {

        //     return General::setResponse('unauthorized', $e->getMessage());

        // }    



    } 


    public function repeatOrder($postData)
    {
        $v = $this->userValidate->required($postData, array('basket_uuid'));
        if ($v->fails()) {
            $data = General::setResponse('validation_error');
            $data['response'] = [
                'message' => $v->errors()->first(),
            ];
            return $data;
            //return General::setResponse('validation_error', $v->errors()->getMessages());
        }
        try {
            return $this->ckoutobj->repeatOrder($postData);
        } catch (\Exception $e) {
            throw new CustomeException("Network error. Please try after some time.",500);
        }

        // try {
        //   $res = $this->ckoutobj->repeatOrder($postData);
        //     if ($res == 'success') {
        //         $data['status'] = "true";
        //         $data['response'] = [
        //             'message' => "Product added to the cart successfully.",
        //         ];
        //         return $data;
        //     }
        //     // elseif($res == 'error'){
        //     //     $data['status'] = "false";
        //     //     $data['response'] = [
        //     //         'message' => "We would like to inform you that your KYC is not completed. In order to add order please complete your KYC.",
        //     //     ];
        //     //     return $data;
        //     // }
        //     else {
        //         $data['status'] = "false";
        //         $data['response'] = [
        //             'message' => "Please try after sometime.",
        //         ];
        //         return $data;
        //     }
        // } catch (\Exception $e) {
        //     return General::setResponse('unauthorized', $e->getMessage());
        // }


    }


}