<?php
namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Accès refusé. Seul l\'administrateur peut accéder aux logs.');
        }
        $logs = Log::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('logs.index', compact('logs'));
    }
}
