<?php

namespace App\Http\Traits;

use App\User;

trait ApiRegistrationTrait
{
    public $vendorRole = 'VENDOR';
    public $supplierRole = 'SUPPLIER';
    public $driverRole = 'DRIVER';

    public function __construct()
    {

    }

    public function vendor($request)
    {
        $request = $request->toArray();
        $user = new User;
        $company = $request['company'];
        $company = array_merge($company, [
                'zipcode_id' => $request['zipcode_id'],
                'city_id' => $request['city_id'],
                'state_id' => $request['state_id'],
                'country_id' => $request['country_id']
        ]);
        $request = array_merge($request,['status' => 'INACTIVE', 'role' => $this->vendorRole, 'password' => bcrypt($request["password"])]);
        $userData = $user->create($request);
        $userData->company()->create($company);
        $userData->sendEmailVerificationNotification();
    }

    public function supplier($request)
    {
        $request = $request->toArray();
        $user = new User;
        $company = $request['company'];
        $company = array_merge($company, [
            'zipcode_id' => $request['zipcode_id'],
            'city_id' => $request['city_id'],
            'state_id' => $request['state_id'],
            'country_id' => $request['country_id']
        ]);

        $request = array_merge($request,['status' => 'INACTIVE', 'role' => $this->supplierRole, 'password' => bcrypt($request["password"])]);
        $userData = $user->create($request);
        $userData->company()->create($company);
        $userData->sendEmailVerificationNotification();
    }


    public function driver($request)
    {
        $user = new User;

        $logisticDetails = $request['logisticDetails'];
        $logisticDetails = array_merge($logisticDetails, [
            'zipcode_id' => $request['zipcode_id'],
            'city_id' => $request['city_id'],
            'state_id' => $request['state_id'],
            'country_id' => $request['country_id']
        ]);

        $request = array_merge($request,['status' => 'INACTIVE', 'role' => $this->driverRole, 'password' => bcrypt($request["password"])]);
        $userData = $user->create($request);
        $userData->logisticDetails()->create($logisticDetails);
        $userData->sendEmailVerificationNotification();
    }
}
