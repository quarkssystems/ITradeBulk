<?php

use App\Models\UserTaxDetails;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTaxDetailsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_tax_details_histories', function (Blueprint $table) {
            $userModel = new User();
            $userTaxDetailsModel = new UserTaxDetails();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('tax_number')->nullable(true);
            $table->string('vat_number')->nullable(true);
            $table->string('passport_number')->nullable(true);
            $table->string('passport_document_file')->nullable(true);
            $table->enum('verify_tax_details', ['YES', 'NO'])->default('NO')->nullable(true);

            $table->uuid('user_id');
            $table->foreign('user_id')->references('uuid')->on($userModel->getTable());

            /**
             * User who has updated this record
             */
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on($userModel->getTable());

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on($userTaxDetailsModel->getTable());
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
        Schema::dropIfExists('user_tax_details_histories');
    }
}
