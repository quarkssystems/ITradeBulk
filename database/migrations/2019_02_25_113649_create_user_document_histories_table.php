<?php

use App\Models\UserDocument;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDocumentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_document_histories', function (Blueprint $table) {
            $userModel = new User();
            $userDocumentModel = new UserDocument();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('title')->nullable(true);
            $table->string('document_file_one')->nullable(true);
            $table->string('document_file_two')->nullable(true);
            $table->string('details')->nullable(true);
            $table->enum('approved', ['YES', 'NO'])->default('NO')->nullable(true);
            $table->text('comment')->nullable(true);
            $table->dateTime('approved_at')->nullable(true);

            $table->uuid('user_id');
            $table->foreign('user_id')->references('uuid')->on($userModel->getTable());

            /**
             * User who has updated this record
             */
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on($userModel->getTable());

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on($userDocumentModel->getTable());
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
        Schema::dropIfExists('user_document_histories');
    }
}
