<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('create:user', function () {
    $name = $this->ask('Nom de l\'utilisateur');
    $email = $this->ask('Email de l\'utilisateur');
    $password = $this->secret('Mot de passe de l\'utilisateur');

    $this->info("Sélectionnez le rôle de l'utilisateur :");
    $this->info("1. Administrateur");
    $this->info("2. Enseignant");
    $this->info("3. Étudiant");

    $role = $this->ask('Entrez le numéro correspondant au rôle');
    $roleName = match ($role) {
        '1' => 'Administrateur',
        '2' => 'Enseignant',
        '3' => 'Étudiant',
        default => null,
    };

    if ($roleName === null) {
        $this->error('Rôle invalide.');
        return;
    }

    $this->info("Sélectionnez le niveau d'enseignement :");
    $this->info("1. Maternelle (40$)");
    $this->info("2. Primaire (45$)");
    $this->info("3. Secondaire général (65$)");
    $this->info("4. Technique (95$)");

    $level = $this->ask('Entrez le numéro correspondant au niveau');
    $fees = match ($level) {
        '1' => 40,
        '2' => 45,
        '3' => 65,
        '4' => 95,
        default => null,
    };

    if ($fees === null) {
        $this->error('Niveau invalide.');
        return;
    }

    // Créer l'utilisateur avec les frais associés
    \App\Models\User::create([
        'name' => $name,
        'email' => $email,
        'password' => bcrypt($password),
        'role' => $roleName,
        'fees' => $fees,
    ]);

    $this->info("Utilisateur créé avec succès avec des frais de $fees$ et le rôle de $roleName.");
})->purpose('Créer un utilisateur avec un rôle spécifique.');
