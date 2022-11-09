<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $productModel = new Product();
            $categoryModel = new Category();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->uuid('product_id')->nullable(true);
            $table->foreign('product_id')->references('uuid')->on($productModel->getTable());

            $table->uuid('category_id')->nullable(true);
            $table->foreign('category_id')->references('uuid')->on($categoryModel->getTable());

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
        Schema::dropIfExists('product_categories');
    }
}
