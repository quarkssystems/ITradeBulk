<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FrontSupplierItemInventoryRequest extends FormRequest
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
//            'single_price' => 'required|numeric',
//            'shrink_price' => 'required|numeric',
//            'case_price' => 'required|numeric',
//            'pallet_price' => 'required|numeric',

//            'single' => 'numeric|required_without_all:shrink,case,pallet',
//            'shrink' => 'numeric|required_without_all:single,case,pallet',
//            'case' => 'numeric|required_without_all:single,shrink,pallet',
//            'pallet' => 'numeric|required_without_all:single,shrink,case',
//            'remarks' => 'required',
        ];

        if($this->request->get('single', 0) > 0)
        {
            $rules['single_price'] = 'required|numeric';
        }
        if($this->request->get('shrink', 0) > 0)
        {
            $rules['shrink_price'] = 'required|numeric';
        }
        if($this->request->get('case', 0) > 0)
        {
            $rules['case_price'] = 'required|numeric';
        }
        if($this->request->get('pallet', 0) > 0)
        {
            $rules['pallet_price'] = 'required|numeric';
        }

        if($this->request->get('shrink', 0) <= 0 && $this->request->get('case', 0) <= 0 && $this->request->get('pallet', 0) <= 0)
        {
//            $rules['single'] .= '|min:1';
//            $rules['single_price'] = 'required|numeric';
        }
        else
        {
//            $rules['single'] .= '|min:0';
        }

        if($this->request->get('single', 0) <= 0 && $this->request->get('case', 0) <= 0 && $this->request->get('pallet', 0) <= 0)
        {
//            $rules['shrink'] .= '|min:1';
//            $rules['shrink_price'] = 'required|numeric';
        }
        else
        {
//            $rules['shrink'] .= '|min:0';
        }

        if($this->request->get('single', 0) <= 0 && $this->request->get('shrink', 0) <= 0 && $this->request->get('pallet', 0) <= 0)
        {
//            $rules['case'] .= '|min:1';
//            $rules['case_price'] = 'required|numeric';
        }
        else
        {
//            $rules['case'] .= '|min:0';
        }

        if($this->request->get('single', 0) <= 0 && $this->request->get('shrink', 0) <= 0 && $this->request->get('case', 0) <= 0)
        {
//            $rules['pallet'] .= '|min:1';
//            $rules['pallet_price'] = 'required|numeric';
        }
        else
        {
//            $rules['pallet'] .= '|min:0';
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'single.required' => 'Single is required field',
            'shrink.required' => 'Shrink is required field',
            'case.required' => 'Case is required field',
            'pallet.required' => 'Pallet is required field',
//            'remarks.required' => 'remarks is required field',
        ];
    }
}
