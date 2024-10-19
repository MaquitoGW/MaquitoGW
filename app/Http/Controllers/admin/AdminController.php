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
        return view('admin.dashboard', [
            'selected' => 1
        ]);
    }

    // Users 

    public function users()
    {
        // Obter usuarios
        $users = User::get();

        return view('admin.users', [
            'users' => $users,
            'selected' => 5
        ]);
    }

    // Habilidades

    public function skills()
    {
        // Obter todas as habilidades
        $skills = Skill::get();

        return view('admin.skills', [
            'skills' => $skills,
            'selected' => 3
        ]);
    }

    // Adicionar uma nova habilidade

    public function skill_add(Request $request) {
        $addSkill = new Skill();

        $addSkill->language = $request->skill;
        $addSkill->year = $request->year;
        $addSkill->info = $request->description;

        $addSkill->save();
    }
}
