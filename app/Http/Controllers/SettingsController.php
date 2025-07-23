<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // Affichage de la page des paramètres système
        return view('settings.index');
    }
}

