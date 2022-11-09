<?php



namespace App\Exceptions;



use Exception;



/**

 * Class GeneralException

 */

class CustomeException extends Exception

{



    /**

     * The status code to use for the response.

     *

     * @var integer

     */



    public $status = 422;



    public $message = 'Unexpected error. Please try after some time.';



    /**

     * Create a new exception instance.

     *

     * @param string $message

     */

    public function __construct($message, $status)

    {

        parent::__construct($message, $status);

    }



    /**

     * In Laravel 5.5, you can render your exceptions directly from the exception class

     * itself, allowing you to handle them they way you want to.

     */

       public function render($message, $status)
    {
        return response()->json([

            'error'   => true,

            'message' => $message

        ], $status);

    }



}

