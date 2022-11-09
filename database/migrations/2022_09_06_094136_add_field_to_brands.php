<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToBrands extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->tinyinteger('on_off')->nullable(true);
        });
        // \DB::raw("ALTER TABLE `brands` ADD `on_off` TINYINT NULL AFTER `updated_at`");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
         Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('on_off')->nullable(true);
        });
    }
}