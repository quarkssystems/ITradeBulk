<?php

use App\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $permissionModel = new Permission();

            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');

            $table->string('name')->nullable(true);
            $table->string('module')->nullable(true);
            $table->json('routes')->nullable(true);
            $table->string('module_group')->nullable(true);
            $table->enum('status', $permissionModel->getStatuses())->nullable(true);
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
        Schema::dropIfExists('permissions');
    }
}
