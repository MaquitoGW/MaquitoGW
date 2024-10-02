<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Apis\McsrvstatController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\functions\UpdateController;
use App\Models\Info;
use App\Models\Skill;
use App\Models\User;
use App\Models\World;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public $versionName;
    public $gameMode;
    public $maxPlayers;
    public $totalOnline;
    public $porta;
    public $mcsrvstat;
    public $data;
    public $discord;

    // Dashboard
    public function dashboard()
    {
        if (Auth::check()) {



            return view('admin.dashboard', [
                'selected' => 1
            ]);
        } else return redirect()->route('login');
    }

    // Users 

    public function users()
    {
        if (Auth::check()) {
            // Obter usuarios
            $users = User::get();

            return view('admin.users', [
                'users' => $users,
                'selected' => 5
            ]);
        } else return redirect()->route('login');
    }

    // Habilidades

    public function skills()
    {
        if (Auth::check()) {
            // Obter todas as habilidades
            $skills = Skill::get();

            return view('admin.skills', [
                'skills' => $skills,
                'selected' => 3
            ]);
        } else return redirect()->route('login');
    }
}
