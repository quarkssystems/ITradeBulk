<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTaxDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_tax_details', function (Blueprint $table) {
            $userModel = new User();

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
        Schema::dropIfExists('user_tax_details');
    }
}
