<?php

namespace App\Http\Requests;

use App\Models\UserDocument;
use Illuminate\Foundation\Http\FormRequest;

class FrontendBecomeDriverRequest extends FormRequest
{
    public $routeName = 'user';
    public $acceptableMimeTypes = [
        'image/png',
        'image/jpeg',
        'image/pjpeg',
        'application/pdf',
    ];
    public $customValidationMessages = [];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userRules = [
            'title' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/'
            ],
            // 'gender' => 'required',
            'logistic_type' => 'required',
            // 'latitude' => 'required',
            // 'longitude' => 'required',
            'termscondition' => 'required',
        ];

        if ($this->request->has('logistic_type') && $this->request->get('logistic_type') == 'COMPANY') {
            $userRules['transporter_name'] = 'required|string|max:255';
        }

        $driverRules = [
            /* 'logisticDetails.phone' => 'required|numeric',
            'logisticDetails.driving_licence' => 'required',
            'logisticDetails.transport_type' => 'required',
            'logisticDetails.transport_capacity' => 'required',
            'logisticDetails.availability' => 'required',
            'logisticDetails.pallets_available' => 'required',
            'logisticDetails.pallets_required' => 'required',
            'logisticDetails.pallets_deposit' => 'required',
            'logisticDetails.work_type' => 'required',*/


            // 'logisticDetails.address1' => 'required',
            // 'zipcode_id' => 'required',
            // 'city_id' => 'required',
            // 'state_id' => 'required',
            // 'country_id' => 'required',
        ];

        $userRules = array_merge($userRules, $driverRules);
        return $userRules;
    }

    public function messages()
    {
        return [
            'password.regex' => 'Password : minimum 8 character long, 1 alphabet, 1 number and 1 special character'
        ];
    }
}
