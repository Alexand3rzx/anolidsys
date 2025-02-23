<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('pregnants', function (Blueprint $table) {
            $table->id();
            $table->string('prgname');
            $table->integer('prgage');
            $table->date('prgbday');
            $table->string('prgaddress');
            $table->string('prgoccupation')->nullable();
            $table->string('prgreligion')->nullable();
            $table->string('prgmother_name')->nullable();
            $table->string('partner_name')->nullable();
            $table->integer('partner_age')->nullable();
            $table->date('partner_bday')->nullable();
            $table->string('partner_occupation')->nullable();
            $table->string('partner_religion')->nullable();
            $table->string('partner_number')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('pregnants');
    }
};
