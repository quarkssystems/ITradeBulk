<?php

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 50; $i++) { 

	    	Notification::create([
	    	    'user_id' => '70b4b532-6b12-4d11-be9b-245d90c28627',
        		'notification' => 'Order Status Changed for Order NO #0000056 is PLACED at '.env("APP_NAME").' on 2020-02-19'
        		// 'notification' => 'Order Status Changed for Order NO #0000056 is PLACED at ITRADEZONE on 2020-02-19'
	        ]);

    	}
    }
}