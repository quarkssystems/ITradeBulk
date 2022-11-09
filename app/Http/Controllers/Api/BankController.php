<?php



namespace App\Http\Controllers\Api;



use App\Classes\UserCls;

use App\Models\BankMaster;

use App\Models\UserBankDetails;

use App\Models\BankBranch;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use DB;





class BankController extends Controller

{

    

    private $userObj;



    public function __construct(UserCls $userObj)

    {



        $this->userObj = $userObj;

    }


    public function addBank(Request $request){
    
        $postData = $request->all();
        $bank = $this->userObj->addBank($postData);
    
        return response($bank);
    }

    public function addBankBranch(Request $request){
    
        $postData = $request->all();
        $bankBranch = $this->userObj->addBankBranch($postData);
    
        return response($bankBranch);
    }


     public function getBankList(Request $request){

     

        $bankModel = new BankMaster();

        $banklist = $bankModel->getBankDropDown();

        

        $bank = array();

        foreach ($banklist as $key => $value) {

            # code...

            $bank[]= array( "uuid" =>$key ,  "Title" => $value);

        }

        $response = $bank;

        return response($response);

       

    }



    public function getBranchList(Request $request){

        $bank_uuid = $request['bank_id'];

     

        $branchModel = new BankBranch();

        $banklist = $branchModel->where('bank_master_id',$bank_uuid)->pluck('branch_name', 'uuid');

         $bank = array();

        foreach ($banklist as $key => $value) {

            # code...

            $bank[]= array( "uuid" =>$key ,  "Title" => $value);

        }

        $response = $bank;

        return response($response);

       

    }



     public function getBankCityList(Request $request){

       

        $bank_uuid = $request['bank_id'];

        $branchModel = new BankBranch();

        $banklist = $branchModel->where('bank_master_id',$bank_uuid)->get();

       

        $bank = array();

        foreach ($banklist as $key => $value) {

            # code...

            $bank[]= array( "uuid" =>$value->city_id ,  "Title" => $value->city_name);

        }

        $response = $bank;

        return response($response);

       

    }



     public function getBankProvinceList(Request $request){

       

        $bank_uuid = $request['bank_id'];

        $branchModel = new BankBranch();

        $banklist = $branchModel->where('bank_master_id',$bank_uuid)->get();

        

        $bank = array();

        foreach ($banklist as $key => $value) {

            # code...

            $bank[]= array( "uuid" =>$value->state_id ,  "Title" => $value->state_name);

        }

        $response = $bank;

        return response($response);

       

    }



    public function getBankPostalCodeList(Request $request){

       

        $bank_uuid = $request['bank_id'];

        $branchModel = new BankBranch();

        $banklist = $branchModel->where('bank_master_id',$bank_uuid)->get();

       

        $bank = array();

        foreach ($banklist as $key => $value) {

            # code...

            $bank[]= array( "uuid" =>$value->zipcode_id ,  "Title" => $value->zipcode_name);

        }

        $response = $bank;

        return response($response);

       

    }



    public function getAccountTypeList(Request $request){

     

        $bankModel = new UserBankDetails();

        $banklist = $bankModel->getAccountTypesDropDown();

        

        $bank = array();

        foreach ($banklist as $key => $value) {

            # code...

            $bank[]= array("Title" => $value);

        }

        $response = $bank;

        return response($response);

       

    }

    







}