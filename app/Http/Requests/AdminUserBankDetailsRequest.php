<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserBankDetailsRequest extends FormRequest
{
    public $acceptableMimeTypes = [
        'image/png',
        'image/jpeg',
        'image/pjpeg',
        'application/pdf',
    ];

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
            'bank_account_name' => 'required',
            'bank_account_number' => 'required|numeric',
            'bank_account_type' => 'required',
            'bank_id' => 'required',
            'bank_branch_id' => 'required',
            'account_confirmation_letter' => 'required|mimetypes:'.implode(',',$this->acceptableMimeTypes),
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
                    $rules['account_confirmation_letter'] = 'mimetypes:mimetypes:'.implode(',',$this->acceptableMimeTypes);
//                    $rules['update_note'] = 'required';
                    break;
                }
            default:
                break;
        }

        return $rules;
    }
}
