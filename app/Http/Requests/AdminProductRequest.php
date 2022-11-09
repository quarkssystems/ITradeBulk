<?php

namespace App\Http\Requests;

// use Auth;
// use Illuminate\Routing\Route;
// use Illuminate\Support\Facades\Route;


use Illuminate\Foundation\Http\FormRequest;

class AdminProductRequest extends FormRequest
{
    public $acceptableMimeTypes = [
        'image/png',
        'image/jpeg',
        'image/jpg',
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
        $routename = \Request::route()->getName();
        $id = auth()->user()->uuid;
        $adminQuickView = \App\AdminQuickView::where('user_id',$id)->first();

            $rules = [
                    // 'barcode' => 'required|numeric|unique:products', //manan-s-mozar 
                    'name' => 'required|max:255',
                    'slug' => 'required|max:255',
                    // 'unit_value' => 'required|max:255', //manan-s-mozar
                    // 'weight' => 'numeric',
                    // 'description' => 'required|max:255',
                    // 'short_description' => 'required|max:255',
                    // 'base_image_file' => 'required|mimetypes:'.implode(',',$this->acceptableMimeTypes),
                    // 'search_keyword' => 'max:255',
                    // 'base_price' => 'required|numeric', //manan-s-mozar
                    // 'meta_title' => 'max:255',
                    // 'meta_keywords' => 'max:255',
                    // 'meta_description' => 'max:255',
        //            'min_price' => 'required|numeric',
        //            'max_price' => 'required|numeric|gt:min_price',
                    // 'brand_id' => 'required|string',

                    /*'single_qty' => 'nullable|numeric',
                    'single_weight' => 'nullable|numeric',
                    'shrink_qty' => 'nullable|numeric',
                    'shrink_weight' => 'nullable|numeric',
                    'case_qty' => 'nullable|numeric',
                    'case_weight' => 'nullable|numeric',
                    'pallet_qty' => 'nullable|numeric',
                    'pallet_weight' => 'nullable|numeric',


                    'single_height' => 'nullable|numeric',
                    'single_width' => 'nullable|numeric',
                    'single_length' => 'nullable|numeric',
                    'shrink_height' => 'nullable|numeric',
                    'shrink_width' => 'nullable|numeric',
                    'shrink_length' => 'nullable|numeric',
                    'case_height' => 'nullable|numeric',
                    'case_width' => 'nullable|numeric',
                    'case_length' => 'nullable|numeric',
                    'pallet_height' => 'nullable|numeric',
                    'pallet_width' => 'nullable|numeric',
                    'pallet_length' => 'nullable|numeric',*/

                    // 'tax_id' => 'required|string', //manan-s-mozar
                    // 'status' => 'required|max:255',

                    // 'stoc_wt' => 'required|numeric', //manan-s-mozar
                    // 'stock_of' => 'required|numeric', //manan-s-mozar
                    // 'stock_type' =>  'required|max:255', //manan-s-mozar
                    // 'stock_gst' => 'nullable|numeric' //manan-s-mozar
                ];

                if(isset($adminQuickView) && $adminQuickView->product_codes == '0'){
                    $rules['barcode'] = 'required|numeric|unique:products';
                }
                if(isset($adminQuickView) && $adminQuickView->product_description == '0'){
                    $rules['description'] = 'required|max:255';
                }
                if(isset($adminQuickView) && $adminQuickView->image_management == '0'){
                    $rules['base_image_file'] = 'required|mimetypes:'.implode(',',$this->acceptableMimeTypes);
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
                    {
                        // $rules['barcode'] = 'required|numeric';
                    }
                    case 'PATCH':
                        {
                            $rules['base_image_file'] = 'mimetypes:'.implode(',',$this->acceptableMimeTypes);
                            break;
                        }
                    default:
                        break;
                }

                return $rules;  
    }
        
}