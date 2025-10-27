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
    Schema::table('infants', function (Blueprint $table) {
        $table->string('infant_code')->unique()->after('id');
    });
}

public function down()
{
    Schema::table('infants', function (Blueprint $table) {
        $table->dropColumn('infant_code');
    });
}
};
