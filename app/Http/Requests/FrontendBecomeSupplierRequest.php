<?php

namespace App\Http\Requests;

use App\Models\UserDocument;
use Illuminate\Foundation\Http\FormRequest;

class FrontendBecomeSupplierRequest extends FormRequest
{
    public $routeName = 'user';
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
        $documentOneExists = request()->get('document_one_exists');

        $userRules = [
            'title' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/'
            ],
            'gender' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'termscondition' => 'accepted',
        ];

        $companyRules = [
            'company.legal_name' => 'required',
            'company.trading_name' => 'required',
            'company.business_type' => 'required',
            'company.product_service_offered' => 'required',
            'company.representative_first_name' => 'required',
            'company.representative_last_name' => 'required',
            'company.email' => 'required',
            'company.phone' => 'required|numeric',
//            'company.website' => 'required|url',
            // 'company.founding_year' => 'required',
//            'company.company_size' => 'required',
//            'company.audience' => 'required',
//            'company.geographical_target' => 'required',
//            'company.owner_user_id' => 'required',
            'company.address1' => 'required',
            'zipcode_id' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
        ];

        $documentTypes = $userDocumentModel->getSupplierDocuments();

        $documentRules = [
            'document_one.*' => 'distinct|min:1',
            'approved.*' => 'distinct|min:1',
        ];

        foreach($documentTypes as $key => $val)
        {
            if(isset($val['required']) && $val['required'] == 'YES' && isset($documentOneExists[$key]) && $documentOneExists[$key] == 'NO')
            {
                $documentRules['document_one.'.$key] = 'required|mimetypes:'.implode(',',$this->acceptableMimeTypes);
            }
            else
            {
                $documentRules['document_one.'.$key] = 'mimetypes:'.implode(',',$this->acceptableMimeTypes);
            }
            $this->customValidationMessages['document_one.'.$key.'.required'] = 'Please upload valid document';
            $this->customValidationMessages['document_one.'.$key.'.mimetypes'] = 'Please upload valid document';

        }

        if($this->request->has('document_two'))
        {
            foreach($this->request->get('document_two') as $key => $val)
            {
                $documentRules['document_two.'.$key] = 'mimetypes:'.implode(',',$this->acceptableMimeTypes);
            }
        }

//        $rules = array_merge($userRules, $companyRules, $documentRules);
        $rules = array_merge($userRules, $companyRules);
        return $rules;
    }

    public function messages()
    {
        return [
            'password.regex' => 'Password : minimum 8 character long, 1 alphabet, 1 number and 1 special character',
            'company.legal_name.required' => 'company legal name field is required',
            'company.trading_name.required' => 'company trading name field is required',
            'company.business_type.required' => 'company business type field is required',
            'company.product_service_offered.required' => 'company product service offered field is required',
            'company.representative_first_name.required' => 'company representative first name field is required',
            'company.representative_last_name.required' => 'company representative last name field is required',
            'company.email.required' => 'company email field is required',
            'company.email.email' => 'company email field must be a valid email address',
            'company.phone.required' => 'company phone field is required',
            'company.phone.numeric' => 'company phone field must be numeric',
            // 'company.founding_year.required' => 'company founding year field is required',
            'company.address1.required' => 'company address field is required',
            'company.phone.required' => 'company phone field is required',
            'termscondition.required' => 'The terms & condition must be accepted',
        ];
    }
}