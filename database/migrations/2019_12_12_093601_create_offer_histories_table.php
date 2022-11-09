<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_histories', function (Blueprint $table) {
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
            $table->timestamps();
             /**
             * User who has updated this record
             */
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on('users');

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on('offerdeals');

            $table->softDeletes();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     Schema::dropIfExists('offer_histories');
        
    }
}
