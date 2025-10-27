<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('medicine_requests', function (Blueprint $table) {
        $table->unsignedBigInteger('batch_id')->nullable()->after('medicine_id');
        $table->foreign('batch_id')->references('id')->on('medicine_batches')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('medicine_requests', function (Blueprint $table) {
        $table->dropForeign(['batch_id']);
        $table->dropColumn('batch_id');
    });
}
};
