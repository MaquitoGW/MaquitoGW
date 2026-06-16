<?php

namespace App\Http\Controllers\admin;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends AdminController
{
    public function index()
    {
        $skills = Skill::get();
        $skillsJson = json_decode(file_get_contents('storage/json/languagens_and_frameworks.json'), true);

        return view('admin.skills', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'skillsJson' => $skillsJson,
            'skills' => $skills
        ]);
    }

    public function add(Request $request)
    {
        if (!Skill::where('code', $request->skill)->count() > 0) {
            $addSkill = new Skill();
            $addSkill->code = $request->skill;
            $addSkill->year = $request->year;
            $addSkill->save();
            
            return back()->with('success', 'Habilidade adicionada com sucesso');
        } else {
            return back()->with('err', 'Habilidade já adicionada');
        }
    }

    public function delete($code)
    {
        Skill::where('code', $code)->delete();
        return back()->with('success', 'Habilidade deletada com sucesso');
    }
}
