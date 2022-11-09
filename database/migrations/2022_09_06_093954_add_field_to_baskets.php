<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToBaskets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::table('baskets', function (Blueprint $table) {
            $table->enum('is_modify',['0','1'])->default(0);
        });
        Schema::table('basket_products', function (Blueprint $table) {
            $table->string('color')->nullable();
        });
        // \DB::raw("ALTER TABLE `baskets` ADD `is_modify` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `updated_at`");
        // \DB::raw("ALTER TABLE `basket_products` ADD `color` VARCHAR(255) NULL AFTER `updated_at`, ADD `size` VARCHAR(255) NULL AFTER `color`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baskets', function (Blueprint $table) {
            $table->dropColumn('is_modify')->nullable(true);
        });
        Schema::table('basket_products', function (Blueprint $table) {
            $table->dropColumn('color')->nullable(true);
        });
    }
}