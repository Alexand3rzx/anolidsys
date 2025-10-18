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
        Schema::create('immunizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('infant_id')->constrained()->onDelete('cascade');
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
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('immunizations');
    }
};

