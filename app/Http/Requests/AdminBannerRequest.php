<?php



namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;



class AdminBannerRequest extends FormRequest

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

        $rules = [

            'name' => 'required|max:255',

            'slug' =>  'required|max:255',

            'image' => 'required|mimetypes:'.implode(',',$this->acceptableMimeTypes),

            'page_name' => 'required|max:255',

            'sequence_number' => 'required|max:255',

            'status' => 'required|max:255',

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

                    $rules['image'] = 'mimetypes:mimetypes:'.implode(',',$this->acceptableMimeTypes);

                    break;

                }

            default:

                break;

        }



        return $rules;

    }

}

