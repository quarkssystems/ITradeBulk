<?php

namespace App\Http\Requests;

// use Auth;
// use Illuminate\Routing\Route;
// use Illuminate\Support\Facades\Route;


use Illuminate\Foundation\Http\FormRequest;

class AdminProductRequest_12_01_2021 extends FormRequest
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
        $routename = \Request::route()->getName();

        if($routename == 'supplier.products.store' || $routename == 'supplier.products.update') {
            $rules = [

                // 'name' => 'required|max:255',
                // 'slug' => 'required|max:255',
                // 'brand_id' => 'required|string',
                // 'base_image_file' => 'required|mimetypes:'.implode(',',$this->acceptableMimeTypes),
                // 'status' => 'required|max:255',

                // 'single_barcode' => 'required|numeric|unique:products', //manan-s-mozar 
                // 'single_base_price' => 'required|numeric', //manan-s-mozar
                // 'single_stock_type' =>  'required|max:255', //manan-s-mozar
                // 'single_stock_of' => 'required|numeric', //manan-s-mozar
                // 'single_default_stock_type' => 'required', //manan-s-mozar
                // 'single_stoc_wt' => 'required|numeric', //manan-s-mozar
                // 'single_stock_gst' => 'nullable|numeric' //manan-s-mozar
                // 'single_tax_id' => 'required|string', //manan-s-mozar
                // 'single_unit_value' => 'required|max:255', //manan-s-mozar
                // 'single_unit_data' => 'required|max:255', //manan-s-mozar
                // // 'single_status' => 'required|max:255',
                // 'single_description' => 'required',
                // 'single_short_description' => 'required',

                // 'pallets_barcode' => 'required|numeric|unique:products', //manan-s-mozar 
                // 'pallets_base_price' => 'required|numeric', //manan-s-mozar
                // 'pallets_stock_type' =>  'required|max:255', //manan-s-mozar
                // 'pallets_stock_of' => 'required|numeric', //manan-s-mozar
                // 'pallets_default_stock_type' => 'required', //manan-s-mozar
                // 'pallets_stoc_wt' => 'required|numeric', //manan-s-mozar
                // 'pallets_stock_gst' => 'nullable|numeric' //manan-s-mozar
                // 'pallets_tax_id' => 'required|string', //manan-s-mozar
                // 'pallets_unit_value' => 'required|max:255', //manan-s-mozar
                // 'pallets_unit_data' => 'required|max:255', //manan-s-mozar
                // // 'pallets_status' => 'required|max:255',
                // 'pallets_description' => 'required',
                // 'pallets_short_description' => 'required',

                // 'case_barcode' => 'required|numeric|unique:products', //manan-s-mozar 
                // 'case_base_price' => 'required|numeric', //manan-s-mozar
                // 'case_stock_type' =>  'required|max:255', //manan-s-mozar
                // 'case_stock_of' => 'required|numeric', //manan-s-mozar
                // 'case_default_stock_type' => 'required', //manan-s-mozar
                // 'case_stoc_wt' => 'required|numeric', //manan-s-mozar
                // 'case_stock_gst' => 'nullable|numeric' //manan-s-mozar
                // 'case_tax_id' => 'required|string', //manan-s-mozar
                // 'case_unit_value' => 'required|max:255', //manan-s-mozar
                // 'case_unit_data' => 'required|max:255', //manan-s-mozar
                // // 'case_status' => 'required|max:255',
                // 'case_description' => 'required',
                // 'case_short_description' => 'required',

                // 'shrink_barcode' => 'required|numeric|unique:products', //manan-s-mozar 
                // 'shrink_base_price' => 'required|numeric', //manan-s-mozar
                // 'shrink_stock_type' =>  'required|max:255', //manan-s-mozar
                // 'shrink_stock_of' => 'required|numeric', //manan-s-mozar
                // 'shrink_default_stock_type' => 'required', //manan-s-mozar
                // 'shrink_stoc_wt' => 'required|numeric', //manan-s-mozar
                // 'shrink_stock_gst' => 'nullable|numeric' //manan-s-mozar
                // 'shrink_tax_id' => 'required|string', //manan-s-mozar
                // 'shrink_unit_value' => 'required|max:255', //manan-s-mozar
                // 'shrink_unit_data' => 'required|max:255', //manan-s-mozar
                // // 'shrink_status' => 'required|max:255',
                // 'shrink_description' => 'required',
                // 'shrink_short_description' => 'required',


            ];

            // switch($this->method())
            // {
            //     case 'GET':
            //     case 'DELETE':
            //     case 'POST':
            //         {
            //             break;
            //         }
            //     case 'PUT':
            //     {
            //         $rules['barcode'] = 'required|numeric';
            //     }
            //     case 'PATCH':
            //         {
            //             $rules['base_image_file'] = 'mimetypes:mimetypes:'.implode(',',$this->acceptableMimeTypes);
            //             break;
            //         }
            //     default:
            //         break;
            // }

            return $rules;
        } else {
            //  dd('sDASAFD');
            $rules = [
                    // 'barcode' => 'required|numeric|unique:products', //manan-s-mozar 
                    'name' => 'required|max:255',
                    'slug' => 'required|max:255',
                    // 'unit_value' => 'required|max:255', //manan-s-mozar
                    'weight' => 'numeric',
                    // 'description' => 'required|max:255',  //commented
                    // 'short_description' => 'required|max:255',  //commented
                    'base_image_file' => 'required|mimetypes:'.implode(',',$this->acceptableMimeTypes),
                    'search_keyword' => 'max:255',
                    // 'base_price' => 'required|numeric', //manan-s-mozar
                    'meta_title' => 'max:255',
                    'meta_keywords' => 'max:255',
                    'meta_description' => 'max:255',
        //            'min_price' => 'required|numeric',
        //            'max_price' => 'required|numeric|gt:min_price',
                    'brand_id' => 'required|string',

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
                        $rules['barcode'] = 'required|numeric';
                    }
                    case 'PATCH':
                        {
                            $rules['base_image_file'] = 'mimetypes:mimetypes:'.implode(',',$this->acceptableMimeTypes);
                            break;
                        }
                    default:
                        break;
                }

                return $rules;
            }   
    }
        
}