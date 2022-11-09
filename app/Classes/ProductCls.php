<?php



namespace App\Classes;



use App\Exceptions\CustomeException;

use App\Repositories\ProductRepository;

use App\General\General;

use App\General\APIRequestValidate;

use App\User;

use App\Models\Product;

use Illuminate\Support\Facades\Auth;

use JWTFactory;

use JWTAuth;



class ProductCls

{



    protected $userValidate;



    public function __construct(ProductRepository $product)

    {

        $this->product = $product;

        $this->userValidate = new APIRequestValidate();

    }



    

    public function getUnreadNotification($postData)

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

            return $this->product->getUnreadNotification($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



    public

    function searchProduct($postData)

    {

        $v = $this->userValidate->required($postData, array('searchtext'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

            return $this->product->searchProduct($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



    public

    function arrivalsProduct($postData)

    {

       /* $v = $this->userValidate->required($postData, array('start_offset'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }*/



        try {

            return $this->product->arrivalsProduct($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



    public

    function bestSalesProduct($postData)

    {

       /* $v = $this->userValidate->required($postData, array('start_offset'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }*/



        try {

            return $this->product->bestSalesProduct($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }





     public

    function dealsOfDayProduct($postData)

    {

        /*$v = $this->userValidate->required($postData, array('start_offset'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }

   */

        try {

            return $this->product->dealsOfDayProduct($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



      public

    function bestOfWeekProduct($postData)

    {

       /* $v = $this->userValidate->required($postData, array('start_offset'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }*/



        try {

            return $this->product->bestOfWeekProduct($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }





     public

    function getCategories($postData)

    {

      /*  $v = $this->userValidate->required($postData, array('start_offset'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }*/



        try {

            return $this->product->getCategories($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



    public

    function getManufacture($postData)

    {

      /*  $v = $this->userValidate->required($postData, array('start_offset'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }*/



        try {

            return $this->product->getManufacture($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



    

      public

    function getSupplierOffers($postData)

    {

       $v = $this->userValidate->required($postData, array('supplier_uuid'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['status'] = "false";

            $data['data'] = [

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

             $res = $this->product->getSupplierOffers($postData);

             return  $res;

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



      public

    function getProductDetails($postData)

    {

        $v = $this->userValidate->required($postData, array('product_uuid'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

            return $this->product->getProductDetails($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }





    public

    function getProductByCat($postData)

    {

        $v = $this->userValidate->required($postData, array('slug'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

            return $this->product->getProductByCat($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



     public

    function getProductByMan($postData)

    {

        $v = $this->userValidate->required($postData, array('slug'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

            return $this->product->getProductByMan($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



      public

    function getRatingReview($postData)

    {

        $v = $this->userValidate->required($postData, array('productid'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

            return $this->product->getRatingReview($postData);

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }



        public

    function givingrating($postData)

    {

        $v = $this->userValidate->required($postData, array('user_uuid','productid','rating','title','review'));

        if ($v->fails()) {

            $data = General::setResponse('validation_error');

            $data['response'] = [

                'message' => $v->errors()->first(),

            ];

            return $data;

            //return General::setResponse('validation_error', $v->errors()->getMessages());

        }



        try {

            $result = $this->product->givingrating($postData);

            if ($result != '') {

                $data = General::setResponse('success');

                $data['response'] = [

                    'message' => "Product Rating Review Added successfully.",

                ];

                return $data;



            } else {

                return General::setResponse('other_error', "Please try after sometime.");

            }

        } catch (\Exception $e) {

            throw new CustomeException("Network error. Please try after some time.",500);

        }

    }

}

   