<?php

use App\Models\Brand;
use App\Models\Product;
use App\Models\Tax;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('products', function (Blueprint $table) {
        //     $productModel = new Product();
        //     $brandModel = new Brand();
        //     $taxModel = new Tax();

        //     $table->increments('id');
        //     $table->uuid('uuid');
        //     $table->index('uuid');

        //     $table->string('name')->nullable(true);
        //     $table->string('slug')->nullable(true);
        //     $table->float('weight')->nullable(true);
        //     $table->text('description')->nullable(true);
        //     $table->text('short_description')->nullable(true);
        //     $table->string('base_image')->nullable(true);
        //     $table->text('search_keyword')->nullable(true);
        //     $table->string('meta_title')->nullable(true);
        //     $table->text('meta_keyword')->nullable(true);
        //     $table->text('meta_description')->nullable(true);
        //     $table->float('min_price')->nullable(true);
        //     $table->float('max_price')->nullable(true);

        //     $table->uuid('brand_id')->nullable(true);
        //     $table->foreign('brand_id')->references('uuid')->on($brandModel->getTable());

        //     $table->uuid('tax_id')->nullable(true);
        //     $table->foreign('tax_id')->references('uuid')->on($taxModel->getTable());

        //     $table->enum('status', $productModel->getStatuses())->nullable(true);

        //     $table->softDeletes();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('products');
    }
}