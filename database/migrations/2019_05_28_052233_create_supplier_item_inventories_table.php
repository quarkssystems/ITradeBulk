<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Models\Product;
class CreateSupplierItemInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_item_inventories', function (Blueprint $table) {

            $userModel  = new User();
            $productModel  = new Product();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->uuid('user_id')->nullable(true);
            $table->foreign('user_id')->references('uuid')->on($userModel->getTable());

            $table->uuid('product_id')->nullable(true);
            $table->foreign('product_id')->references('uuid')->on($productModel->getTable());

            $table->text('single')->nullable(true);
            $table->text('shrink')->nullable(true);
            $table->text('case')->nullable(true);
            $table->text('pallet')->nullable(true);
            $table->string('remarks')->nullable(true);

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
        Schema::dropIfExists('supplier_item_inventories');
    }
}
