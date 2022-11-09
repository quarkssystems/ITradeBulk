<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FrontCheckoutAddToCartRequest extends FormRequest
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
            'single_qty' => 'numeric|required_without_all:shrink_qty,case_qty,pallet_qty',
            'shrink_qty' => 'numeric|required_without_all:single_qty,case_qty,pallet_qty',
            'case_qty' => 'numeric|required_without_all:single_qty,shrink_qty,pallet_qty',
            'pallet_qty' => 'numeric|required_without_all:single_qty,shrink_qty,case_qty',
        ];

        if($this->request->get('shrink_qty', 0) <= 0 && $this->request->get('case_qty', 0) <= 0 && $this->request->get('pallet_qty', 0) <= 0)
        {
            $rules['single_qty'] .= '|min:1';
        }
        else
        {
            $rules['single_qty'] .= '|min:0';
        }

        if($this->request->get('single_qty', 0) <= 0 && $this->request->get('case_qty', 0) <= 0 && $this->request->get('pallet_qty', 0) <= 0)
        {
            $rules['shrink_qty'] .= '|min:1';
        }
        else
        {
            $rules['shrink_qty'] .= '|min:0';
        }

        if($this->request->get('single_qty', 0) <= 0 && $this->request->get('shrink_qty', 0) <= 0 && $this->request->get('pallet_qty', 0) <= 0)
        {
            $rules['case_qty'] .= '|min:1';
        }
        else
        {
            $rules['case_qty'] .= '|min:0';
        }

        if($this->request->get('single_qty', 0) <= 0 && $this->request->get('shrink_qty', 0) <= 0 && $this->request->get('case_qty', 0) <= 0)
        {
            $rules['pallet_qty'] .= '|min:1';
        }
        else
        {
            $rules['pallet_qty'] .= '|min:0';
        }
        return $rules;
    }
}
