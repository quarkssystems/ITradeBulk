<?php

use App\Models\Promotion;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions_histories', function (Blueprint $table) {

            //
            $userModel = new User();
            $promotionModel = new Promotion();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');
            $table->string('promotion_id')->nullable(true);
            $table->string('promotion_type')->nullable(true);
            $table->date('period_from')->nullable(true);
            $table->date('period_to')->nullable(true);
            $table->double('promotion_price')->nullable(true);

            /**
             * User who has updated this record
             */
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on($userModel->getTable());

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on($promotionModel->getTable());

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
        Schema::dropIfExists('promotions_histories');
    }
}
