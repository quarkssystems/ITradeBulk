<?php

namespace App\Http\Requests;

use App\Models\LocationCountry;
use App\Models\LocationState;
use Illuminate\Foundation\Http\FormRequest;

class AdminLocationCityRequest extends FormRequest
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
        $countryModel = new LocationCountry();
        $stateModel = new LocationState();
        $rules = [
            'city_name' => 'required',
            'country_id' => 'required|exists:'.$countryModel->getTable().',uuid',
            'state_id' => 'required|exists:'.$stateModel->getTable().',uuid',
            'status' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ];
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            case 'POST':
                {
                    break;
                }
            case 'PUT':
            case 'PATCH':
                {
//                    $rules['update_note'] = 'required';
                    $rules['country_id'] = '';
                    $rules['state_id'] = '';
                    break;
                }
            default:
                break;
        }

        return $rules;
    }
}
