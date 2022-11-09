<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\UserCls;
use Illuminate\Support\Facades\Auth;

/**
  @OA\Info(
      description="",
      version="1.0.0",
      title="iTradeZon App APIs",
 )
 */

/**
  @OA\SecurityScheme(
      securityScheme="bearerAuth",
          type="http",
          scheme="bearer",
          bearerFormat="JWT"
      ),
 */

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    protected $userObj;

    public function __construct(UserCls $userObj)
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->userObj = $userObj;
    }

    /**
      @OA\Post(
          path="/api/v1/login",
          tags={"Login"},
          summary="Login",
          operationId="login",

          @OA\Parameter(
              name="email",
              in="query",
              required=true,
              @OA\Schema(
                  type="string"
              )
          ),
          @OA\Parameter(
              name="password",
              in="query",
              required=true,
              @OA\Schema(
                  type="string"
              )
          ),
          @OA\Response(
              response=200,
              description="Success",
              @OA\MediaType(
                  mediaType="application/json",
              )
          ),
          @OA\Response(
              response=401,
              description="Unauthorized"
          ),
          @OA\Response(
              response=400,
              description="Invalid request"
          ),
          @OA\Response(
              response=404,
              description="not found"
          ),
      )
     */

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
      
        $credentials = request(['email', 'password']);
         $postData = $request->all();
        $result = $this->userObj->checkUserRequired($postData);
        
        if($result!=''){
             return response()->json($result);  
        }

        if (! $token = auth('api')->attempt($credentials)) {

                  $data['status'] = 'false';
                  $data['response'] = [
                    'message' => 'User email/password is wrong. Please enter correct credentials',
                  ];
                return response()->json($data, 401);  
             
        }else{

            
               $data = $this->userObj->checkUserStatus($postData);
             if($data){

                 $data1['status'] = 'false';
                  $data1['response'] =array( 'message' =>'Please Verify Registration mail for Activation');

              return response($data1);

             }else{

             //$postData = $request->all(); 
             $login = $this->userObj->login($postData);
            }
        }

        return $this->respondWithToken($token);
    }


    public function loginBk(Request $request)
    {
        $postData = $request->all();
        $postData['device_type'] = 'Android';
        $login = $this->userObj->login($postData);
        $message = isset($login['message']) ? $login['message'] : '';
        $response = $login['response'];
        $code = $login['code'];

       /* print_r($response);
        die;*/


        return response()->json($response);
       
    }



    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
      $user = auth('api')->user();
     
      $userCompanyDetail = $this->userObj->getCompanyDetail($user->uuid);
      $userBankDetail = $this->userObj->getBankDetail($user->uuid);
      $userDocDetail = $this->userObj->getDocDetail($user->uuid ,$user->role);
      
      $userTaxDetail = $this->userObj->getTaxDetail($user->uuid);
      $address_of_driver ='';
       $userdata = $this->userObj->getUserData($user->uuid);
      if($user->role == "DRIVER" or $user->role =='COMPANY')
       {
          $address_of_driver = $this->userObj->getAddressDetail($user->uuid);  
          $userdata = array_merge($userdata,$address_of_driver);
       } 
      

     
      return response()->json([
            'status' => 'true',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user'=> $userdata,
            'user_company_detail'=>$userCompanyDetail,
            'user_bank_detail'=>$userBankDetail,
            'user_document_detail'=>$userDocDetail,
            'user_tax_detail'=>$userTaxDetail
        ]);
    }

  
}
