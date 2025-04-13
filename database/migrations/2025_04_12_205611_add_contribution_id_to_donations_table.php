<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('donations', function (Blueprint $table) {
        $table->unsignedBigInteger('contribution_id')->after('id');
        $table->foreign('contribution_id')->references('id')->on('contributions')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('donations', function (Blueprint $table) {
        $table->dropForeign(['contribution_id']);
        $table->dropColumn('contribution_id');
    });
}
};
