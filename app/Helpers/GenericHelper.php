<?php

use App\Models\Basket;
use App\Models\LocationZipcode;
use App\Models\Category;
use App\Models\UserCompany;
use App\Models\Setting;
use App\Models\Product;

function getBasketProductCount()
{
    if(!is_null(session()->get('basket_id', null)))
    {
        $basketId = session()->get('basket_id', null);
        $basketModel = new Basket();
        if($basketModel->where('uuid', $basketId)->first() != null){

            return $basketModel->where('uuid', $basketId)->first()->getTotalProductCount();
        } else {
            return 0;
        }
    }
    else
    {
        $basket_product = 0;
        $basketModel = new Basket();
        $basket_data = $basketModel->getBasket(); //get current user entry of basket
        if($basket_data->first())
        {
            $basketId = $basket_data['0']; 
            $basketModel = new Basket();
            $basket_product = $basketModel->where('uuid', $basketId)->first()->getTotalProductCount();
            session(['basket_id' => $basketId]);
        }

        return $basket_product;    
        
    }
}

function getLocationsDropdown()
{
    $locationZipcodeModel = new LocationZipcode();
    return $locationZipcodeModel->getDropDownWithZipCode();
}

function getSupplierName($uuid){
    $supplierName = UserCompany::select('legal_name')->where('owner_user_id',$uuid)->first();
    return $supplierName['legal_name'];
}

function checkUserLoggedIn(){
    if(\Auth::check()){
        return 1;
        // echo 'Logged In';
    }else{
        return 0;
        // echo 'Need To Log In';
    }
}

function uploadZip($request){
    function rmdir_recursive($dir) {
        foreach(scandir($dir) as $file) {
           if ('.' === $file || '..' === $file) continue;
           if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
           else unlink("$dir/$file");
       }
    
       rmdir($dir);
    }
    
    if(isset($_FILES["zip_file"]["name"])) {
        $filename = $_FILES["zip_file"]["name"];
        $source = $_FILES["zip_file"]["tmp_name"];
        $type = $_FILES["zip_file"]["type"];
    
        $name = explode(".", $filename);
        $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
        foreach($accepted_types as $mime_type) {
            if($mime_type == $type) {
                $okay = true;
                break;
            } 
        }
    
        $continue = strtolower($name[1]) == 'zip' ? true : false;
        if(!$continue) {
            $message = "The file you are trying to upload is not a .zip file. Please try again.";
            $arr = ['status' => '422','msg' => $message]; //added
            return $arr; //added
        }
    
      /* PHP current path */
      $path = public_path().'/uploads/media/'.$request['foldername'].'/'.auth()->user()->uuid.'/';
      
      if (!is_dir($path)) {
            //Create our directory if it does not exist
            mkdir($path, 0777,true);
            // echo "Directory created";
        }
        // absolute path to the directory where zipper.php is in
      $filenoext = basename ($filename, '.zip');  // absolute path to the directory where zipper.php is in (lowercase)
      $filenoext = basename ($filenoext, '.ZIP');  // absolute path to the directory where zipper.php is in (when uppercase)
    
      $targetdir = $path . $filenoext; // target directory
      $targetzip = $path . $filename; // target zip file
        // echo "targetdir: ".$targetdir."<br>";
        // echo "targetzip: ".$targetzip."<br>";
        // echo "path: ".$path."<br>";
        
      /* create directory if not exists', otherwise overwrite */
      /* target directory is same as filename without extension */
    
      if (is_dir($targetdir))  rmdir_recursive ( $targetdir);
    
    
      mkdir($targetdir, 0777);
    
    
      /* here it is really happening */
    // die;
        if(move_uploaded_file($source, $targetzip)) {
            $zip = new ZipArchive();
            $x = $zip->open($targetzip);  // open the zip file to extract
            if ($x === true) {
                $zip->extractTo($path); // place in the directory with same name  
                $zip->close();
                // dd($path.''.$name[0]);
                $filesNew = File::allFiles($path.''.$name[0]);
                foreach($filesNew as $new){
                    // dd($path.''.$name[0].'/'.$new->getFileName());

                     $productData = Product::where('base_image', 'like', '%' . $new->getFileName() . '%')->select('user_id','base_image')->first();
                   
                      if($productData != null && $productData->base_image != null){
                          $success = \File::move($path.''.$name[0].'/'.$new->getFileName(),public_path().'/'.$productData->base_image);
                        //   $success = \File::move($path.''.$name[0].'/'.$new->getFileName(),$path.''.$new->getFileName());
                      } 
                }
                rmdir($path.''.$name[0]);
    
                if(file_exists($targetzip)){
                    unlink($targetzip);
                    // unlink($path.''.$name);
            }
            }
            $arr = ['status' => '200','msg' => "Your .zip file was uploaded and unpacked."];
        } else {
            $arr = ['status' => '400','msg' => "There was a problem with the upload. Please try again."];
        }
    }
    return $arr;
    // echo $message;
}

function uploadFile($request,$folder,$fileRealName,$order_number){
    function rmdir_recursive($dir) {
        foreach(scandir($dir) as $file) {
           if ('.' === $file || '..' === $file) continue;
           if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
           else unlink("$dir/$file");
       }
    
       rmdir($dir);
    }
    
    $arr = [];
    if(isset($_FILES["file"]["name"])) {
        $filename = $_FILES["file"]["name"];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        $source = $_FILES["file"]["tmp_name"];
        $type = $_FILES["file"]["type"];
    
        $name = explode(".", $filename);
    
      /* PHP current path */
      $path = public_path().'/'.$folder.'/'.$fileRealName.'/';
      if (!is_dir($path)) {
            //Create our directory if it does not exist
            mkdir($path, 0777, true);
            // echo "Directory created";
        }
        // absolute path to the directory where zipper.php is in
   
      $target = $path . $fileRealName.''.$ext; // target zip file
 
        if(move_uploaded_file($source, public_path($folder).'/'.$fileRealName.'/'. $order_number.'.'.$ext)) {
          
            $arr = ['status' => '200','msg' => "Your file was uploaded."];
        } else {
            $arr = ['status' => '400','msg' => "There was a problem with the upload. Please try again."];
        }
    }
    return $arr;
    // echo $message;
}

function getMonthName($number){
    $name = '';
    switch ($number) {
        case 1:
            $name = 'Jan';
            break;
        case 2:
            $name = 'Feb';
            break;
        case 3:
            $name = 'Mar';
            break;
        case 4:
            $name = 'Apr';
            break;
        case 5:
            $name = 'May';
            break;
        case 6:
            $name = 'Jun';
            break;
        case 7:
            $name = 'Jul';
            break;
        case 8:
            $name = 'Aug';
            break;
        case 9:
            $name = 'Sep';
            break;
        case 10:
            $name = 'Oct';
            break;
        case 11:
            $name = 'Nov';
            break;
        case 12:
            $name = 'Dec';
            break;
    }
    return $name;
}

function checkImageExists($img,$type = 'product'){
    
    if (!empty($img) && file_exists(public_path().'/'.$img)){
    // if(file_exists('uploads/users-pic/'.auth()->user()->code_melli.'.jpg'))
        return asset($img);
    }else{
        // return asset($img);
        if($type == 'product'){
            return asset('images/product.jpg');
        }elseif($type == 'user'){
            return 'uploads/user.png';
        }
    }
}


function AddDeviceInOneSignal($arr){
	$fields = array(
		'app_id' => "1a1dc5b3-d65e-42f6-a7e3-34aa97795ec1",
		'identifier' => $arr['device_token'],
		'language' => "en",
		'timezone' => "-28800",
		'game_version' => "1.0",
		'device_os' => $arr['device_os'],
		'device_type' => $arr['device_type'],
		'device_model' => $arr['device_model'],
		'test_type' => 1,
		'country' => $arr['country']
		// 'tags' => array("foo" => "bar")
	); 
		
		$fields = json_encode($fields);

		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players"); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
		curl_setopt($ch, CURLOPT_HEADER, FALSE); 
		curl_setopt($ch, CURLOPT_POST, TRUE); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		
		$response = curl_exec($ch); 
		curl_close($ch); 
		$response = json_decode($response);
        
        if(!isset($response->errors)){
            return $response->id;    
        }else{
            return 0;
        }
		

}

function sendNotification($data){

		$content = array(
			"en" => $data['msg']
			);

        $arr_data = array("order_uuid" => $data['order_uuid'] ,"notification_uuid" =>  $data['notification_uuid'] );
	    if(isset($data['driver']))
        {
            $arr_data = array("order_uuid" => $data['order_uuid'] ,"notification_uuid" =>  $data['notification_uuid'] ,"acceptable" =>"true" );  
        } 

       
        $button = array();    
        //if(isset($data['button'])){
            /*$button = array(
               "id" => "accept_id",
               "text" =>"Accept",
                "icon" => "",
                "url" => "google.com",
                 
            ); */        //}

			$fields = array(
				'app_id' => "1a1dc5b3-d65e-42f6-a7e3-34aa97795ec1",
				'include_player_ids' => array($data['player_id']),
				'data' => $arr_data,
				'contents' => $content,
                'buttons' => $button
			);
		
		$fields = json_encode($fields);
    	// print("\nJSON sent:\n");
    	// print($fields);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
}

function sendWalletNotification($data){

        $content = array(
            "en" => $data['msg']
            );

        // $arr_data = array("notification_uuid" =>  $data['notification_uuid'] );
        // if(isset($data['driver']))
        // {
        //     $arr_data = array("notification_uuid" =>  $data['notification_uuid'] ,"acceptable" =>"true" );  
        // } 

       
        $button = array();    
        //if(isset($data['button'])){
            /*$button = array(
               "id" => "accept_id",
               "text" =>"Accept",
                "icon" => "",
                "url" => "google.com",
                 
            ); */        //}

            $fields = array(
                'app_id' => "1a1dc5b3-d65e-42f6-a7e3-34aa97795ec1",
                'include_player_ids' => array($data['player_id']),
                // 'data' => $arr_data,
                'contents' => $content,
                'buttons' => $button
            );
        
        $fields = json_encode($fields);
        // print("\nJSON sent:\n");
        // print($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
}



function basic_email() {
    $data = array('name'=>"Itradezon.com");
 
    Mail::send(['text'=>'mail'], $data, function($message) {
       $message->to('abc@gmail.com', 'Tutorials Point')->subject
          ('Laravel Basic Testing Mail');
       $message->from('info@itradezon.com','Itradezon.com');
    });
    echo "Basic Email Sent. Check your inbox.";
 }
 function html_email() {
    $data = array('name'=>"Itradezon");
    Mail::send('mail', $data, function($message) {
       $message->to('abc@gmail.com', 'Tutorials Point')->subject
          ('Laravel HTML Testing Mail');
       $message->from('info@itradezon.com','Itradezon');
    });
    echo "HTML Email Sent. Check your inbox.";
 }
 function attachment_email($data) {
    Mail::send([], $data, function($message) use ($data) {
       $message->to('hoxet21916@clsn1.com',$data['user']['first_name'])->subject
          ($data['subject'])
          ->setBody('Hi,'.$data['user']['first_name'].'<br> Please check attachment.<br>Thanks');
       $message->attach($data['file']);
       $message->from('info@itradezon.com','Itradezon');
    });
    return "Email Sent with attachment. Check your inbox.";
 }



if (! function_exists('setting')) {

    function setting($key, $default = null)
    {
        if (is_null($key)) {
            return new Setting();
        }

        if (is_array($key)) {
            return Setting::set($key[0], $key[1]);
        }

        $value = Setting::get($key);

        return is_null($value) ? value($default) : $value;
    }
}


function sendOrderStatusEmail($messageData,$email,$subject) {
    \Log::info('sendOrderStatusEmail in');
 
    Mail::send([], [], function ($message) use ($messageData,$email,$subject) {
        $message->to($email)
         ->subject($subject)
         ->setBody($messageData, 'text/html'); // for HTML rich messages
         $message->from(env('MAIL_USERNAME'), env('APP_NAME'));
        //  $message->from(env('MAIL_USERNAME'),'Itradezon');
     });
    return true;
 }