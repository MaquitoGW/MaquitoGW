<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Info;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Função inicial
    public function index($id = null)
    {
        // Obter informações
        $infos = Info::first();
        $contacts = Contact::first();
        $projects = Project::get();
        $skills = Skill::orderBy('year', 'asc')->get();
        $skillsJson = json_decode(file_get_contents('storage/json/languagens_and_frameworks.json'), true);


        // Detalhes do projeto
        if (!empty($id)) $project = Project::where('demo', $id)->first();
        else $project = false;

        return view('site.index', [
            'infos' => $infos,
            'contacts' => $contacts,
            'projects' => $projects,
            'project' => $project,
            'skills' => $skills,
            'skillsJson' => $skillsJson
        ]);
    }




    // Demo do projeto 
    public function demo(Request $request) {}
}
