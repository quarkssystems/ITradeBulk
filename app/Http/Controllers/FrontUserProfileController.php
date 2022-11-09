<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrontUserRequest;
use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Models\LogisticDetails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontUserProfileController extends Controller
{
    public $route = 'user.profile';
    /**
     * @param User $user
     * @return View
     */
    public function edit(Request $request,User $userModel,LogisticDetails $logistic_detail, LocationCountry $locationCountryModel, LocationState $locationStateModel, LocationCity $locationCityModel) : View
    {
        $user = $userModel->where('uuid', auth()->user()->uuid)->first();
        $logistic_detail = $logistic_detail->select('address1','address2' ,'country_id' ,'state_id','city_id','zipcode_id')->where('user_id', auth()->user()->uuid)->first();

            $gender = $userModel->getGenderDropDown();
        $title = $userModel->getTitleDropDown();
         $roles = $userModel->getRoleDropDown();
        $status = $userModel->getStatusesDropDown();
        $route = $this->route;
        $role = auth()->user()->role;
        $pageTitle = "Profile";
        $type=array();
        $countries = $locationCountryModel->getDropdown();
        $states = [];
        $cities = [];
        $zipcodes = [];

        $countryInput = $request->old('country_id');
        $stateInput = $request->old('state_id');
        $cityInput = $request->old('city_id');
        if(!is_null($countryInput)) {
            $states = $locationCountryModel->where('uuid', $countryInput)->first()->getStateDropDown();
        }
        if(!is_null($stateInput)) {
            $cities = $locationStateModel->where('uuid', $stateInput)->first()->getCityDropDown();
        }
        if(!is_null($cityInput)) {
            $zipcodes = $locationCityModel->where('uuid', $cityInput)->first()->getZipcodeDropDown();
        }

         if($logistic_detail){

            $countryInput = $request->has('country_id') ? $request->get('country_id') : $logistic_detail->country_id;
            $stateInput = $request->has('state_id') ? $request->get('state_id') : $logistic_detail->state_id;
            $cityInput = $request->has('city_id') ? $request->get('city_id') : $logistic_detail->city_id;
           

            if(!is_null($countryInput)) {
                $states = $locationCountryModel->where('uuid', $countryInput)->first()->getStateDropDown();
            }
            if(!is_null($stateInput)) {
                $cities = $locationStateModel->where('uuid', $stateInput)->first()->getCityDropDown();
            }
            if(!is_null($cityInput)) {
                $zipcodes = $locationCityModel->where('uuid', $cityInput)->first()->getZipcodeDropDown();
            }


	        
	        if($logistic_detail->country_id){
	                $user->setAttribute('country_id',$logistic_detail->country_id);    
	        }
	        if($logistic_detail->state_id){
	                $user->setAttribute('state_id',$logistic_detail->state_id);    
	        }
	        if($logistic_detail->city_id){
	                $user->setAttribute('city_id',$logistic_detail->city_id);    
	        }

	         if($logistic_detail->zipcode_id){
	                $user->setAttribute('zipcode_id',$logistic_detail->zipcode_id);    
	        }    
        
        }
        $copy = request()->has('copy') ? true : false;

        return view('user.profile.index', compact('user', 'logistic_detail', 'gender', 'title', 'type', 'roles', 'status', 'pageTitle', 'route', 'role', 'copy', 'countries', 'states', 'cities', 'zipcodes'));
    }

    /**
     * @param AdminUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(FrontUserRequest $request, User $profile,LogisticDetails $logistic_detail)
    {

         $user_id = $request->get('uuid'); 
         if ($request->has('logisticDetails')) {         
	         $logisticDetails = $request->get('logisticDetails');    
	         $logisticDetails = array_merge($logisticDetails, ['zipcode_id' => $request->get('zipcode_id'),
	            'city_id' => $request->get('city_id'),
	            'state_id' => $request->get('state_id'),
	            'country_id' => $request->get('country_id')
	               ]);
        }
         if(empty($request->get('password')))
        {
            // $request->flashOnly('password');
            unset($request['password']);
            unset($request['password_confirmation']);
        }
        else
        {
            $request->replace(['password' => bcrypt($request->get('password'))]);
        }

        
       // dd($request);
        if($request->hasFile('image_file') && $request->file('image_file')->isValid())
        {
            $documentFile = $profile->uploadMedia($request->file('image_file'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['image' => $document]);
        }
        $userData = $profile->update($request->except(['email', 'status']));
        
	    if ($request->has('logisticDetails')) {         
	        $logistic_detail->where('user_id',$user_id)->update($logisticDetails); 
	    }
       // $userData->logisticDetails()->update($logisticDetails);
        $route = $this->route;
        return redirect(route("$route.edit", $profile->uuid))->with(['status' => 'success', 'message' => trans('success.admin|user|updated')]);
    }
}
