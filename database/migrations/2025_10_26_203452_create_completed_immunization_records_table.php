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
    Schema::create('completed_immunization_records', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pregnant_id')->constrained()->onDelete('cascade');
        $table->json('records'); // store JSON snapshot of completed immunizations
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
        Schema::dropIfExists('completed_immunization_records');
    }
};
