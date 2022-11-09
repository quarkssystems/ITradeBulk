<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class createProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            // $productModel = new Product();
            $brandModel = new \App\Models\Brand();
            $taxModel = new \App\Models\Tax();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('user_id')->nullable(true);
            $table->string('store_id')->nullable(true);
            
            $table->string('name')->nullable(true);
            $table->string('slug')->nullable(true);
            $table->string('unit')->nullable(true);
            $table->string('unit_name')->nullable(true);
            $table->string('unit_value')->nullable(true);

            $table->text('search_keyword')->nullable(true);
            $table->string('meta_title')->nullable(true);
            $table->text('meta_keyword')->nullable(true);
            $table->text('meta_description')->nullable(true);
            // $table->string('tax_id')->nullable(true);
            $table->double('stoc_wt')->nullable(true);
            $table->string('stock_of')->nullable(true);
            $table->string('stock_type')->nullable(true);
            $table->integer('stock_gst')->nullable(true);
            $table->tinyinteger('default_stock_type')->nullable(true);
            $table->string('subdepartment')->nullable(true);
            $table->string('spec_sheet_url')->nullable(true);

            // new added
            $table->enum('audited',['0', '1'])->nullable(true);
            $table->enum('published',['0', '1'])->nullable(true);
            $table->enum('has_upc',['Yes', 'No'])->nullable(true);
            $table->string('barcode')->nullable(true);
            $table->string('product_code')->nullable(true);
            $table->string('store_item_code')->nullable(true);
            $table->string('parent_id')->nullable(true);
            $table->string('variant_id')->nullable(true);
            $table->string('unit_barcode_link')->nullable(true);
            $table->text('description')->nullable(true);
            // $table->string('brand')->nullable(true);
            $table->string('manufacturer')->nullable(true);
            $table->string('category_group')->nullable(true);
            $table->string('department')->nullable(true);
            $table->string('category')->nullable(true);
            $table->string('subcategory')->nullable(true);
            $table->string('segment')->nullable(true);
            $table->string('subsegment')->nullable(true);
            $table->double('vat')->nullable(true);
            $table->double('cost')->nullable(true);
            $table->string('markup')->nullable(true);
            $table->double('autoprice')->nullable(true);
            $table->double('price')->nullable(true);
            $table->double('base_price')->nullable(true);
            $table->string('quantity')->nullable(true);
            $table->string('min_order_quantity')->nullable(true);
            $table->date('stock_expiry_date')->nullable(true);
            $table->string('packing')->nullable(true);
            $table->string('units_per_packing')->nullable(true);
            $table->double('size')->nullable(true);
            $table->string('unit_of_measure')->nullable(true);
            $table->string('size_description')->nullable(true);
            $table->double('height')->nullable(true);
            $table->double('width')->nullable(true);
            $table->double('depth')->nullable(true);
            $table->float('weight')->nullable(true);
            $table->string('colour')->nullable(true);
            $table->string('colour_variants')->nullable(true);
            $table->string('size_variants')->nullable(true);
            $table->string('product_specification')->nullable(true);
            $table->string('warranty')->nullable(true);
            $table->string('attributes')->nullable(true);
            $table->string('base_image')->nullable(true);
            // $table->string('image_file_name')->nullable(true);
            
            $table->string('alternate_image_1')->nullable(true);
            $table->string('alternate_image_2')->nullable(true);
            $table->string('promotion_type')->nullable(true);
            $table->string('promotion_id')->nullable(true);
            $table->date('period_from')->nullable(true);
            $table->date('period_to')->nullable(true);
            $table->double('promotion_price')->nullable(true);
            $table->string('courier_safe')->nullable(true);
            $table->date('out_of_stock_lead_time')->nullable(true);
            $table->string('is_permanent_lead_product')->nullable(true);
            $table->string('product_delivery_type')->nullable(true);
            $table->string('arrival_type')->nullable(true);
            

            // $table->float('weight')->nullable(true);
            // $table->text('description')->nullable(true);
            // $table->text('short_description')->nullable(true);
            // $table->string('base_image')->nullable(true);
            // $table->text('search_keyword')->nullable(true);
            // $table->string('meta_title')->nullable(true);
            // $table->text('meta_keyword')->nullable(true);
            // $table->text('meta_description')->nullable(true);
            // $table->float('min_price')->nullable(true);
            // $table->float('max_price')->nullable(true);

            $table->uuid('brand_id')->nullable(true);
            $table->foreign('brand_id')->references('uuid')->on($brandModel->getTable());

            $table->uuid('tax_id')->nullable(true);
            $table->foreign('tax_id')->references('uuid')->on($taxModel->getTable());

            $table->enum('status', ['ACTIVE','INACTIVE'])->nullable(true);

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
        Schema::dropIfExists('products');
    }
}