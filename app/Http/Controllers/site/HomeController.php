<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Info;
use App\Models\Project;
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

        // Obter frases
        $json = json_decode(file_get_contents('storage/json/frases.json'), true);
        $countRand = rand(0, count($json) - 1);
        $frases = $json[$countRand];

        // Detalhes do projeto
        if (!empty($id)) $project = Project::where('demo', $id)->first();
        else $project = false;

        return view('site.index', [
            'infos' => $infos,
            'contacts' => $contacts,
            'projects' => $projects,
            'project' => $project,
            'frase' => $frases['frase'],
            'autor' => $frases['autor']
        ]);
    }




    // Demo do projeto 
    public function demo(Request $request) {}
}
