<?php

use App\Models\Brand;
use App\Models\Product;
use App\Models\Tax;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('product_histories', function (Blueprint $table) {
        //     $productModel = new Product();
        //     $brandModel = new Brand();
        //     $taxModel = new Tax();
        //     $userModel = new User();

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

        //     /**
        //      * User who has updated this record
        //      */
        //     $table->uuid('updated_by');
        //     $table->foreign('updated_by')->references('uuid')->on($userModel->getTable());

        //     $table->text('update_note')->nullable(true);

        //     $table->uuid('history_of');
        //     $table->foreign('history_of')->references('uuid')->on($productModel->getTable());

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
        // Schema::dropIfExists('product_histories');
    }
}