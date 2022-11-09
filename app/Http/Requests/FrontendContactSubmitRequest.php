<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FrontendContactSubmitRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
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
                    break;

                }
            default:
                break;
        }
        return $rules;
    }
}
