<?php

use App\Models\LocationCountry;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_countries', function (Blueprint $table) {
            $locationCountryModel = new LocationCountry();
            $table->increments('id');
            $table->uuid('uuid');
            $table->index('uuid');
            $table->string('country_name');
            $table->enum('status', $locationCountryModel->getStatuses())->nullable(true);
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
        Schema::dropIfExists('location_countries');
    }
}
