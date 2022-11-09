<?php

use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $categoryModel = new Category();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('name')->nullable(true);
            $table->string('slug')->nullable(true);

            $table->uuid('parent_category_id')->nullable(true);
            $table->foreign('parent_category_id')->references('uuid')->on($categoryModel->getTable());

            $table->string('banner_image_file')->nullable(true);
            $table->string('thumb_image_file')->nullable(true);
            $table->text('description')->nullable(true);
            $table->text('short_description')->nullable(true);
            $table->string('meta_title')->nullable(true);
            $table->text('meta_description')->nullable(true);
            $table->text('meta_keywords')->nullable(true);
            $table->enum('status', $categoryModel->getStatuses())->nullable(true);

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
        Schema::dropIfExists('categories');
    }
}
