<?php

namespace App\General;

use Validator;

class APIRequestValidate {

    public $acceptableMimeTypes = [
        'image/png',
        'image/jpeg',
        'image/pjpeg',
        'application/pdf',
    ];

    public function required($data, $field = array()) {
        $messages = ['required' => trans('validation.required')];
        $fieldArr = array_fill_keys($field, 'required');
        $validator = Validator::make($data, $fieldArr, $messages);
        return $validator;
    }

    public function email($data, $field = array()) {
        $messages = ['email' => trans('validation.invalid_format')];
        $fieldArr = array_fill_keys($field, 'email');
        $validator = Validator::make($data, $fieldArr, $messages);
        return $validator;
    }

    public function different($data, $field1, $field2) {
        $rules = array(
            $field2 => 'different:' . $field1,
        );

        $messages = ['different' => trans('validation.different')];
        $validator = Validator::make($data, $rules, $messages);
        
        return $validator;
    }

    public function same($data, $field1, $field2) {
        $rules = array(
            $field2 => 'same:' . $field1,
        );

        $messages = ['same' => trans('validation.same')];
        $validator = Validator::make($data, $rules, $messages);
        
        return $validator;
    }

    public function unique($data,$field1){

        if(isset($data['user_id']) && $data['user_id'] != ""){
          
           $validator = Validator::make($data, [
                $field1 => 'unique:users,email,'.$data['user_id'].',uuid,deleted_at,NULL'
            ]);
            return $validator;
        }else{
            $validator = Validator::make($data, [
                $field1 => 'unique:users,email'
            ]);
           
            return $validator;
        }

    }

    public function image($data, $field = array()) {
        $messages = ['Document image' => trans('validation.invalid_format')];
        $rules  = array();
        
        foreach($field  as $key =>$imgval)
        {
            $rules[$key]  = 'mimetypes:'.implode(',',$this->acceptableMimeTypes);
               
        }
        
        $validator = Validator::make($data, $rules, $messages);
        
        return $validator;
    }

}
