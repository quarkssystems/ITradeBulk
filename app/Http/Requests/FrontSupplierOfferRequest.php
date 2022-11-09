<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FrontSupplierOfferRequest extends FormRequest
{
     public $acceptableMimeTypes = [
        'image/png',
        'image/jpeg',
        'image/pjpeg',
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
            'title' => 'required',
            'startdate' => 'required',
            'enddate' => 'required',
            'starttime' => 'required',
            'endtime' => 'required',
            'offer_type'=> 'required',
            'offer_value'=> 'required',
            'offercode'=> 'required_if:offer_method,==,COUPON CODE',
            // 'offercode'=> 'required_if:offer_method,==,COUPON CODE|alpha_num|unique:offerdeals|regex:/^(?=.*[A-Z])(?=.*\d).+$/ ',
                
            'image_file' => 'mimetypes:'.implode(',',$this->acceptableMimeTypes),
           
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
                    $rules['image_file'] = 'mimetypes:mimetypes:'.implode(',',$this->acceptableMimeTypes);
                    break;
                    
                }
            default:
                break;
        }
        return $rules;
    }

   // public function messages()
   //  {
   //      return [
   //          'offercode.alpha_num' => 'The Offercode Allow only Caps letter & Numeric',
   //           'offercode.regex' => 'The Offercode Allow only Caps letter & Numeric',
   //      ];
   //  }

}
