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
       Schema::create('medicine_batches', function (Blueprint $table) {
    $table->id();
    $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
    $table->integer('batch_number')->default(1);
    $table->integer('stock')->default(0);
    $table->date('expiration')->nullable();
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
        Schema::dropIfExists('medicine_batches');
    }
};
