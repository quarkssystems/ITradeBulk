<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserTaxDetailsRequest extends FormRequest
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
            'tax_number' => 'required',
            'vat_number' => 'required',
            'passport_number' => 'required',
            'passport_document' => 'required|mimetypes:'.implode(',',$this->acceptableMimeTypes),
            'verify_tax_details' => 'required',
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
                    $rules['passport_document'] = 'mimetypes:mimetypes:'.implode(',',$this->acceptableMimeTypes);
//                    $rules['update_note'] = 'required';
                    break;
                }
            default:
                break;
        }

        return $rules;
    }
}
