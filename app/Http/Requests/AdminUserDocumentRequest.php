<?php

namespace App\Http\Requests;

use App\Models\UserDocument;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class AdminUserDocumentRequest extends FormRequest
{
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
        $userDocumentModel = new UserDocument();
        $userModel = new User();
        $userId = $this->route('user_uuid');
        $user = $userModel->where('uuid', $userId)->first();
        $documentTypes = [];
        $documentOneExists = request()->get('document_one_exists');
        switch($user->role)
        {
            case 'VENDOR':
                $documentTypes = $userDocumentModel->getVendorDocuments();
                break;

            case 'SUPPLIER':
                $documentTypes = $userDocumentModel->getSupplierDocuments();
                break;

            case 'LOGISTICS':
                $documentTypes = $userDocumentModel->getLogisticsDocuments();
                break;

            case 'ADMIN':
            default:
                break;
        }

        $rules = [
            'document_one.*' => 'distinct|min:1',
            'approved.*' => 'distinct|min:1',
        ];

        if($this->request->has('approved'))
        {
            foreach($this->request->get('approved') as $key => $val)
            {
                $rules['approved.'.$key] = 'required';
            }
        }

        foreach($documentTypes as $key => $val)
        {
            if(isset($val['required']) && $val['required'] == 'YES' && isset($documentOneExists[$key]) && $documentOneExists[$key] == 'NO')
            {
                $rules['document_one.'.$key] = 'required|mimetypes:'.implode(',',$this->acceptableMimeTypes);
            }
            else
            {
                $rules['document_one.'.$key] = 'mimetypes:'.implode(',',$this->acceptableMimeTypes);
            }
            $this->customValidationMessages['document_one.'.$key.'.required'] = 'Please upload valid document';
            $this->customValidationMessages['document_one.'.$key.'.mimetypes'] = 'Please upload valid document';

        }


        if($this->request->has('document_two'))
        {
            foreach($this->request->get('document_two') as $key => $val)
            {
                $rules['document_two.'.$key] = 'mimetypes:'.implode(',',$this->acceptableMimeTypes);
            }
        }

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
//                    $rules['document_one'] = 'mimetypes:mimetypes:'.implode(',',$this->acceptableMimeTypes);
//                    $rules['document_two'] = 'mimetypes:mimetypes:'.implode(',',$this->acceptableMimeTypes);
                    break;
                }
            default:
                break;
        }

        return $rules;
    }

    public function messages()
    {
        $messages = parent::messages();
        $messages['document_one.*.distinct'] = 'Duplicate document found';
        $messages = array_merge($messages, $this->customValidationMessages);
        return $messages;
    }
}
