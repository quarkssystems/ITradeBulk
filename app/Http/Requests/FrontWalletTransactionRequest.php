<?php



namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;



class FrontWalletTransactionRequest extends FormRequest

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

            'credit_amount' => 'required|numeric|min:1',

            'transaction_type' => 'required',

            'remarks' => 'required'

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

                    break;

                }

            case 'PUT':

            case 'PATCH':

                {

                    break;



                }

            default:

                break;

        }

        return $rules;

    }



}

