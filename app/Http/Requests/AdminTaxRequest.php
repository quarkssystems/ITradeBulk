<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminTaxRequest extends FormRequest
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
            'name' => 'required|max:255',
            'value' => 'required|max:100|numeric',
            'status' => 'required|max:255',
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
                    break;
                }
            default:
                break;
        }

        return $rules;
    }
}
