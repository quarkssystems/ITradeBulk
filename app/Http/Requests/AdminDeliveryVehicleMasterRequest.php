<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminDeliveryVehicleMasterRequest extends FormRequest
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
            'vehicle_type' => 'required|max:255',
            'capacity' => 'required|numeric',
            'price_per_km' => 'required|numeric',
            'pallet_capacity_standard' => 'required|numeric',
            'body_volumn' => 'required',
            'combine_payload' => 'required',
            'combine_pallets' => 'required',
        ];

        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
            case 'POST': {
                    break;
                }
            case 'PUT':
            case 'PATCH': {
                    break;
                }
            default:
                break;
        }

        return $rules;
    }
}
