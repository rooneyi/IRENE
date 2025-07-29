<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('students')->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->date('date_paiement');
            $table->enum('statut', ['Payé', 'En attente', 'Incomplet'])->default('En attente');
            $table->foreignId('agent_encaisseur')->constrained('users');
            $table->string('numero_recu')->unique();
            $table->string('remarque')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

