<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class CreateShortcodeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         //$userModel = new User();
        Schema::create('shortcode_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');
            $table->string('shortcode_label')->nullable(true);
            $table->string('shortcode_name')->nullable(true);
            $table->timestamps();

             /**
             * User who has updated this record
             */
            $table->uuid('updated_by');
            $table->foreign('updated_by')->references('uuid')->on('users');

            $table->text('update_note')->nullable(true);

            $table->uuid('history_of');
            $table->foreign('history_of')->references('uuid')->on('email_template');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shortcode_histories');
    }
}
