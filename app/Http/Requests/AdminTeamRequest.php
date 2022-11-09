<?php



namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;



class AdminTeamRequest extends FormRequest

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

            'first_name' => 'required|max:255',

            'last_name' =>  'required|max:255',

            'designation' => 'required',

            'coloured_image' => 'required|mimetypes:'.implode(',',$this->acceptableMimeTypes),

            'black_white_image' => 'required|mimetypes:'.implode(',',$this->acceptableMimeTypes),

            'status' => 'required|max:255'

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

                    $rules['coloured_image'] = 'mimetypes:mimetypes:'.implode(',',$this->acceptableMimeTypes);

                    $rules['black_white_image'] = 'mimetypes:mimetypes:'.implode(',',$this->acceptableMimeTypes);

                    break;

                }

            default:

                break;

        }



        return $rules;

    }

}

