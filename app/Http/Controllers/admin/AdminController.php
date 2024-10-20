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

        // Obter habilidades para o selected
        $skillsJson = json_decode(file_get_contents('storage/json/languagens_and_frameworks.json'), true);

        return view('admin.skills', [
            'skillsJson' => $skillsJson,
            'skills' => $skills,
            'selected' => 3
        ]);
    }

    // Adicionar uma nova habilidade

    public function skill_add(Request $request) {
        $addSkill = new Skill();

        $addSkill->code = $request->skill;
        $addSkill->year = $request->year;

        $addSkill->save();
        return back()->with('success', 'Habilidade adicionada com sucesso');
    }

    // Apagar habilidade
    public function skill_delete($code) {
        Skill::where('code', $code)->delete();

        return back()->with('success', 'Habilidade deletada com sucesso');
    }
}
