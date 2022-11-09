<?php



namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;



class ImportCsvSupplierRequest extends FormRequest

{

  

   public $acceptableMimeTypes = [

        'application/vnd.ms-excel',

        'text/plain',

        'text/tsv',

    ];



     public $acceptableMime = [

        'xlsx',

        'xls',

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

              'real_product_csv_file' => 'required|mimes:'.implode(',',$this->acceptableMime),

        ];



        return $rules;

    }

    public function messages(){
        return [
            'real_product_csv_file.required' => 'The product csv file field is required.',
            'real_product_csv_file.mimes' => 'The product csv file should be xlsx or xlx.'
        ];
    }

}