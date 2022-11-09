<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('products', function (Blueprint $table) {
        //     $table->enum('has_upc', ['Yes', 'No'])->default('Yes');
        //     $table->string('product_code')->nullable();
        //     $table->string('store_item_code')->nullable();
        //     $table->string('unit_barcode_link')->nullable();
        //     $table->double('size')->nullable();
        //     $table->string('size_description')->nullable();
        //     $table->double('height')->nullable();
        //     $table->double('width')->nullable();
        //     $table->double('depth')->nullable();
        //     $table->string('product_brand')->nullable();
        //     $table->string('colour')->nullable();
        //     $table->string('colour_variants')->nullable();
        //     $table->string('size_variants')->nullable();
        //     $table->string('department')->nullable();
        //     $table->string('subdepartment')->nullable();
        //     $table->string('category')->nullable();
        //     $table->string('subcategory')->nullable();
        //     $table->string('segment')->nullable();
        //     $table->string('subsegment')->nullable();
        //     $table->string('spec_sheet_url')->nullable();
        //     $table->string('warranty')->nullable();
        //     $table->string('alternate_image_1')->nullable();
        //     $table->string('alternate_image_2')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('products', function (Blueprint $table) {
        //     $table->dropColumn('has_upc');
        //     $table->dropColumn('product_code');
        //     $table->dropColumn('store_item_code');
        //     $table->dropColumn('unit_barcode_link');
        //     $table->dropColumn('size');
        //     $table->dropColumn('size_description');
        //     $table->dropColumn('height');
        //     $table->dropColumn('width');
        //     $table->dropColumn('depth');
        //     $table->dropColumn('product_brand');
        //     $table->dropColumn('colour');
        //     $table->dropColumn('colour_variants');
        //     $table->dropColumn('size_variants');
        //     $table->dropColumn('department');
        //     $table->dropColumn('subdepartment');
        //     $table->dropColumn('category');
        //     $table->dropColumn('subcategory');
        //     $table->dropColumn('segment');
        //     $table->dropColumn('subsegment');
        //     $table->dropColumn('spec_sheet_url');
        //     $table->dropColumn('warranty');
        //     $table->dropColumn('alternate_image_1');
        //     $table->dropColumn('alternate_image_2');
        // });
    }
}