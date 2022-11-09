<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickingDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picking_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');
            $table->string('order_id')->nullable(true);
            $table->string('product_id')->nullable(true);
            $table->string('basket_id')->nullable(true);
            $table->string('basket_products_id')->nullable(true);
            $table->string('single_qty')->nullable(true);
            $table->string('old_qnty')->nullable(true);
            $table->string('product_price')->nullable(true);
            $table->string('cart_amount')->nullable(true);
            $table->string('shipment_amount')->nullable(true);
            $table->string('discount_amount')->nullable(true);
            $table->string('tax_amount')->nullable(true);
            $table->string('final_total')->nullable(true);
            $table->string('old_final_total')->nullable(true);
            $table->string('status')->nullable(true);
            $table->string('color')->nullable(true);
            $table->string('size')->nullable(true);
            // $table->string('promotion_type')->nullable(true);
            // $table->date('period_from')->nullable(true);
            // $table->date('period_to')->nullable(true);
            // $table->double('promotion_price')->nullable(true);
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
        Schema::dropIfExists('picking_documents');
    }
}