<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $userModel = new User();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');
            $table->enum('title', $userModel->getTitles());
            $table->string('first_name')->nullable(true);
            $table->string('last_name')->nullable(true);
            $table->enum('gender', $userModel->getGenders());
            $table->string('email')->nullable(true);
            $table->string('mobile')->nullable(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('password_updated_at')->nullable();
            $table->string('password')->nullable(true);
            $table->enum('status', $userModel->getStatuses());
            $table->string('role')->nullable(true);
            $table->text('remarks')->nullable(true);

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
