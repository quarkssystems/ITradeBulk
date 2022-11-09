<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_documents', function (Blueprint $table) {
            $userModel = new User();

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
        Schema::dropIfExists('user_documents');
    }
}
