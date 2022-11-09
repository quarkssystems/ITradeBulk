<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminQuickViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_quick_views', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');
            $table->string('user_id')->nullable(true);
            $table->enum('admin_fields',[0,1])->default(0);
            $table->enum('product_codes',[0,1])->default(0);
            $table->enum('product_links',[0,1])->default(0);
            $table->enum('product_description',[0,1])->default(0);
            $table->enum('data_hierarchy',[0,1])->default(0);
            $table->enum('variants',[0,1])->default(0);
            $table->enum('attributes',[0,1])->default(0);
            $table->enum('image_management',[0,1])->default(0);
            $table->enum('promotions',[0,1])->default(0);
            $table->enum('invoice_splitting',[0,1])->default(0);
            $table->enum('pallet_configuration',[0,1])->default(0);
            $table->enum('fact',[0,1])->default(0);
            $table->enum('barcode',[0,1])->default(1);
            $table->enum('description',[0,1])->default(1);
            $table->enum('front_image',[0,1])->default(1);
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
        Schema::dropIfExists('admin_quick_views');
    }
}