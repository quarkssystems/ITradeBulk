<?php

namespace App\General;

class General
{
    public static function setResponse($type, $message = '')
    {
        switch (strtoupper($type)) {
            case 'SUCCESS':
                $code = 200;
                $status = 'true';
                break;
            case 'NO_CONTENT':
                $code = 204;
                $status = 'false';
                $message = 'No data found';
                break;
            case 'VALIDATION_ERROR':
                $status = 'false';
                $code = 422;
                break;
            case 'OTHER_ERROR':
                $status = 'false';
                $code = 423;
                // $message = 'Something went wrong';
                break;
            case 'FORBIDDEN':
                $status = 'false';
                $code = 403;
                break;
            case 'UNAUTHORIZED':
                $status = 'false';
                $code = 401;
                break;
            case 'CONFLICT':
                $status = 'false';
                $code = 409;
                break;
            case 'NOT_FOUND':
                $code = 404;
                $status = 'false';
                break;
            default:
                break;
        }
      //  $data['code'] = $code;
        $data['status'] = $status;
       
        if ($message != '') {
          //  $data['response'] = ($code == 422) ? $message : array('message' => $message);
            $data['response'] = array('message' => $message);
        }
        return $data;
    }

}
