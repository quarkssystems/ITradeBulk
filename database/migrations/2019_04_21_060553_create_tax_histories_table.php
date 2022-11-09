<?php

use App\Models\Tax;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_histories', function (Blueprint $table) {
            $taxModel = new Tax();
            $userModel = new User();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('name')->nullable(true);
            $table->string('value')->nullable(true);
            $table->enum('status', $taxModel->getStatuses())->nullable(true);

            /**
             * User who has updated this record
             */
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on($userModel->getTable());

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on($taxModel->getTable());

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
        Schema::dropIfExists('tax_histories');
    }
}
