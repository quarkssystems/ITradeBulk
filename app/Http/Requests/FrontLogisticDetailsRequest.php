<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FrontLogisticDetailsRequest extends FormRequest
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
            // 'phone' => 'required|numeric',
            // 'driving_licence' => 'required',
            // 'transport_type' => 'required',
            // 'transport_capacity' => 'required',
            // 'vehicle_type' => 'required',
            // 'pallet_capacity_standard' => 'required',
            // 'availability' => 'required',
            /* 'pallets_available' => 'required',
            'pallets_required' => 'required',
            'pallets_deposit' => 'required',*/
            // 'work_type' => 'required',
            // 'user_id' => 'required',
            /* 'address1' => 'required',
            'zipcode_id' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',*/];

        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                    break;
                }
            case 'POST': {
                    break;
                }
            case 'PUT':
            case 'PATCH': {
                    //                    $rules['update_note'] = 'required';
                    break;
                }
            default:
                break;
        }
        return $rules;
    }
}
