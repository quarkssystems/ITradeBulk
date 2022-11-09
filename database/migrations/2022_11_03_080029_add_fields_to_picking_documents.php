<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToPickingDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('picking_documents', function (Blueprint $table) {
            $table->string('offer_price')->nullable(true);
            $table->string('offer_id')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('picking_documents', function (Blueprint $table) {
            $table->dropColumn('offer_price')->nullable(true);
            $table->dropColumn('offer_id')->nullable(true);
        });
    }
}
