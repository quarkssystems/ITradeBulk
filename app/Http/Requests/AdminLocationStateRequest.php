<?php

namespace App\Http\Requests;

use App\Models\LocationCountry;
use Illuminate\Foundation\Http\FormRequest;

class AdminLocationStateRequest extends FormRequest
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
        $rules = [
            'state_name' => 'required',
            'country_id' => 'required|exists:'.$countryModel->getTable().',uuid',
            'status' => 'required',
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

                    break;
                }
            default:
                break;
        }

        return $rules;
    }
}
