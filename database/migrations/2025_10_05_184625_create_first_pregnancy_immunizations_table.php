<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('first_pregnancy_immunizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pregnant_id');
            $table->integer('visit_number')->comment('Check-up count (e.g., 1st, 2nd, etc.)');
            $table->date('visit_date')->nullable();
            $table->string('expected_month', 255)->nullable();
            $table->string('vaccine_given', 100)->nullable()->comment('Example: TT1, TT2, Iron, etc.');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('pregnant_id')
                ->references('id')
                ->on('pregnants')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('first_pregnancy_immunizations');
    }
};
