<?php



namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;



class AdminImportCsvRequest extends FormRequest

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

              'file_import' => 'required|mimes:'.implode(',',$this->acceptableMime),

        ];



        return $rules;

    }

}