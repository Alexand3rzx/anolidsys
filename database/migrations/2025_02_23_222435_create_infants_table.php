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
        Schema::create('infants', function (Blueprint $table) {
            $table->id();
            $table->string('child_name');
            $table->date('child_bday');
            $table->string('child_place');
            $table->string('child_address');
            $table->string('child_mother');
            $table->string('child_father');
            $table->enum('child_gender', ['Male', 'Female']);
            $table->decimal('child_height', 5, 2); // Height in cm (e.g., 60.5 cm)
            $table->decimal('child_weight', 5, 2); // Weight in kg (e.g., 3.2 kg)
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
        Schema::dropIfExists('infants');
    }
};
