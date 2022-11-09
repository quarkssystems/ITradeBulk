<?php
use App\User;
use App\Models\History\UserHistory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserlinkToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userModel = new User();
        $userHistoryModel = new UserHistory();
        Schema::table($userModel->getTable(), function (Blueprint $table) {
          
            $table->string('facebook_url')->nullable(true)->after('remember_token');
            $table->string('twitter_url')->nullable(true)->after('remember_token');
            $table->string('insta_url')->nullable(true)->after('remember_token');
        });

        Schema::table($userHistoryModel->getTable(), function (Blueprint $table) {
          
            $table->string('facebook_url')->nullable(true)->after('remember_token');
            $table->string('twitter_url')->nullable(true)->after('remember_token');
            $table->string('insta_url')->nullable(true)->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $userModel = new User();
        $userHistoryModel = new UserHistory();
        Schema::table($userModel->getTable(), function (Blueprint $table) {
             $table->dropColumn(['facebook_url', 'twitter_url', 'insta_url']);
        });
        Schema::table($userHistoryModel->getTable(), function (Blueprint $table) {
             $table->dropColumn(['facebook_url', 'twitter_url', 'insta_url']);
        });
    }
}
