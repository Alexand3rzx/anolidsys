<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1️⃣ Drop the medicine_id from medicine_batches
        Schema::table('medicine_batches', function (Blueprint $table) {
            if (Schema::hasColumn('medicine_batches', 'medicine_id')) {
                $table->dropForeign(['medicine_id']);
                $table->dropColumn('medicine_id');
            }
        });

        // 2️⃣ Ensure pivot table exists and is correct
        if (!Schema::hasTable('medicine_batch_medicine')) {
            Schema::create('medicine_batch_medicine', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('medicine_id');
                $table->unsignedBigInteger('batch_id');
                $table->timestamps();

                $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
                $table->foreign('batch_id')->references('id')->on('medicine_batches')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('medicine_batches', function (Blueprint $table) {
            $table->unsignedBigInteger('medicine_id')->nullable();
        });

        Schema::dropIfExists('medicine_batch_medicine');
    }
};