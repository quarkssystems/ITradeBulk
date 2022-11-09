<?php

use App\Models\Category;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_histories', function (Blueprint $table) {
            $categoryModel = new Category();
            $userModel = new User();

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

            /**
             * User who has updated this record
             */
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on($userModel->getTable());

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on($categoryModel->getTable());

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
        Schema::dropIfExists('category_histories');
    }
}
