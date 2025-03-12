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
            
            Schema::table('infants', function (Blueprint $table) {
                // Immunization fields
                $table->date('bcg_date')->nullable();
                $table->date('hepatitis_b_date')->nullable();
                $table->date('pentavalent_date_1')->nullable();
                $table->date('pentavalent_date_2')->nullable();
                $table->date('pentavalent_date_3')->nullable();
                $table->date('opv_date_1')->nullable();
                $table->date('opv_date_2')->nullable();
                $table->date('opv_date_3')->nullable();
                $table->date('ipv_date_1')->nullable();
                $table->date('ipv_date_2')->nullable();
                $table->date('pcv_date_1')->nullable();
                $table->date('pcv_date_2')->nullable();
                $table->date('pcv_date_3')->nullable();
                $table->date('mmr_date_1')->nullable();
                $table->date('mmr_date_2')->nullable();
            });
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
