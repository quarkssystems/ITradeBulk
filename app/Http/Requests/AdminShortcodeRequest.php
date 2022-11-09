<?php



namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;



class AdminShortcodeRequest extends FormRequest

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

            'slug' => 'required|unique:shortcode|max:255',

            // 'type' => 'required',

            'shortcode_label' => 'required',

            'shortcode_name' => 'required|unique:shortcode',

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

                    $rules['icon'] = 'mimetypes:mimetypes:'.implode(',',$this->acceptableMimeTypes);

                    break;

                }

            default:

                break;

        }



        return $rules;

    }

}

