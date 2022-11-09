<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferdealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
            Schema::create('offerdeals', function (Blueprint $table) {
           

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');
            $table->uuid('user_id')->nullable(true);
            $table->string('title')->nullable(true);
            $table->timestamp('start_date')->nullable(true);
            $table->timestamp('end_date')->nullable(true);
           
            $table->string('brands_id')->nullable(true);
            $table->string('categories_id')->nullable(true);
            $table->string('products_id')->nullable(true);
            $table->string('offer_type')->nullable(true);
            $table->string('offer_value')->nullable(true);
            $table->text('description')->nullable(true);
            $table->text('image')->nullable(true);
            $table->enum('status', ['active', 'inactive']);

            $table->softDeletes();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offerdeals', function (Blueprint $table) {
            //
              Schema::dropIfExists('offerdeals');
        });
    }
}
