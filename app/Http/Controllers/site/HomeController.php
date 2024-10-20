<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Info;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{
    // Função inicial
    public function index(Request $request, $id = null)
    {
        // Verificar o idioma do BD
        $languageUserGET = str_replace("-", "_", App::getLocale());
        if ($languageUserGET == 'pt_BR' || $languageUserGET == 'en_US') $language = $languageUserGET;
        else $language = env('APP_FAKER_LOCALE');

        // Obter informações
        $infos = Info::where('language', $language)->first();

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
