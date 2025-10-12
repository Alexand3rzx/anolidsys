<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medicine_requests', function (Blueprint $table) {
            $table->id();

            // Medicine being requested
            $table->unsignedBigInteger('medicine_id');
            $table->foreign('medicine_id')
                ->references('id')
                ->on('medicines')
                ->onDelete('cascade');

            // Who requested (useradmin)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Requested amount
            $table->integer('quantity');

            // Status: pending | approved | awaiting_pickup | completed | rejected
            $table->enum('status', [
                'pending',
                'approved',
                'awaiting_pickup',
                'completed',
                'rejected'
            ])->default('pending');

            // New fields for approval & completion process
            $table->string('pickup_code', 50)->nullable(); // unique code for pickup
            $table->date('pickup_date')->nullable();        // pickup schedule date
            $table->timestamp('completed_at')->nullable();  // when pickup is confirmed done

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_requests');
    }
};
