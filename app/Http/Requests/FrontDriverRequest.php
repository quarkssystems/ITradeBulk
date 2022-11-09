<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FrontDriverRequest extends FormRequest
{
    public $routeName = 'user';

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
            'title' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
             'email' => 'required|email|unique:users',
             'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/'
            ],
            'gender'=> 'required',
            'status' => 'required'
            
        ];


       
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
                {
                    break;
                }
            case 'POST':
                {
                   /* if($this->has('uuid'))
                    {
                        $rules['email'] = 'required|email|unique:users,email,'.$this->get('uuid').',uuid';
                        $rules['password'] = [
                            'nullable',
                            'min:8',
                            'confirmed',
                            'regex:/[a-z]/',      // must contain at least one lowercase letter
                            'regex:/[A-Z]/',      // must contain at least one uppercase letter
                            'regex:/[0-9]/',      // must contain at least one digit
                            'regex:/[@$!%*#?&]/'
                        ];
//                        $rules['update_note'] = 'required';
                    }*/
                    break;
                }
            case 'PUT':
            case 'PATCH':
                {

                     
                    if($this->has('uuid'))
                    {
                    
                     $rules['email'] = 'required|email|unique:users,email,'.$this->get('uuid').',uuid';
                        $rules['password'] = [
                            'nullable',
                            'min:8',
                            'confirmed',
                            'regex:/[a-z]/',      // must contain at least one lowercase letter
                            'regex:/[A-Z]/',      // must contain at least one uppercase letter
                            'regex:/[0-9]/',      // must contain at least one digit
                            'regex:/[@$!%*#?&]/'
                        ];
//                        $rules['update_note'] = 'required';
                    }
                    break;

                }
            default:
                break;
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'password.regex' => 'Password : minimum 8 character long, 1 alphabet, 1 number and 1 special character'
        ];
    }
}
