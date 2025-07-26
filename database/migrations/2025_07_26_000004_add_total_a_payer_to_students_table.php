<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->float('total_a_payer')->default(0);
            $table->integer('mois_repartition')->default(10); // Par défaut, 10 mois scolaires
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['total_a_payer', 'mois_repartition']);
        });
    }
};

