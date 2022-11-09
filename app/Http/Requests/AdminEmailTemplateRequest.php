<?php



namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;



class AdminEmailTemplateRequest extends FormRequest

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

            'name' => 'required|max:255',

            'slug' => 'required|max:255',

            // 'type' => 'required',

            'description' => 'required',

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

