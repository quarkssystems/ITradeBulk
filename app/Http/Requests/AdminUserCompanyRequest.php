<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserCompanyRequest extends FormRequest
{
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
        $rules = [
            'legal_name' => 'required',
            'trading_name' => 'required',
            'business_type' => 'required',
            'product_service_offered' => 'required',
            'representative_first_name' => 'required',
            'representative_last_name' => 'required',
            'email' => 'required',
            'phone' => 'required|numeric',
            'founding_year' => 'required',
            //'company_size' => 'required',
            //'audience' => 'required',
           //'geographical_target' => 'required',
            'owner_user_id' => 'required',
            'address1' => 'required',
            'zipcode_id' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
        ];

        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
                {
                    break;
                }
            case 'POST':
                {
                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
//                    $rules['update_note'] = 'required';
                    break;
                }
            default:
                break;
        }
        return $rules;
    }
}
