<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShortcodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shortcode', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('slug')->nullable(true);
            $table->string('shortcode_label')->nullable(true);
            $table->string('shortcode_name')->nullable(true);
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
        Schema::dropIfExists('shortcode');
    }
}
