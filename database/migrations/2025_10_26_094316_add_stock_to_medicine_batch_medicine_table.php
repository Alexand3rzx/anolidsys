<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('medicine_batch_medicine', function (Blueprint $table) {
            if (!Schema::hasColumn('medicine_batch_medicine', 'stock')) {
                $table->integer('stock')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('medicine_batch_medicine', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
};
