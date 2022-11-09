<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_rating', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_uuid')->nullable(true);
            $table->string('productid')->nullable(true);
            $table->enum('rating', ['1','2','3','4','5'])->nullable(true);
            $table->text('review')->nullable(true);
            $table->enum('status', ['active','inactive'])->nullable(true);
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
        Schema::dropIfExists('review_rating');
    }
}
