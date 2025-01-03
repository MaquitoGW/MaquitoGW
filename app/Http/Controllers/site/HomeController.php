<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Customization;
use App\Models\Info;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{
    // Função inicial
    public function index(Request $request)
    {
        // Verificar o idioma do BD
        if (env("MULTIPLE_LANGUAGES") == 1) {
            $languageUserGET = str_replace("-", "_", App::getLocale());
            switch ($languageUserGET) {
                case 'en_US':
                case 'pt_BR':
                    $language = $languageUserGET;
                    break;

                default:
                    $language = env('APP_FAKER_LOCALE');
                    break;
            }
        } else $language = env('APP_FAKER_LOCALE');

        // Obter informações
        $infos = Info::where('language', $language)->first();
        $contacts = Contact::first();
        $projects = Project::get();

        $skills = Skill::orderBy('year', 'asc')->get();
        $skillsJson = json_decode(file_get_contents('storage/json/languagens_and_frameworks.json'), true);

        // verificar se existe dados 

        if (!$infos || !$contacts) abort(404);

        return view('site.index', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'infos' => $infos,
            'contacts' => $contacts,
            'projects' => $projects,
            'skills' => $skills,
            'skillsJson' => $skillsJson
        ]);
    }

    // Demo do projeto 
    public function details($id)
    {
        // Verificar o idioma do BD
        $languageUserGET = str_replace("-", "_", App::getLocale());
        if ($languageUserGET == 'pt_BR' || $languageUserGET == 'en_US') $language = $languageUserGET;
        else $language = env('APP_FAKER_LOCALE');

        // Obter informações
        $infos = Info::where('language', $language)->first();
        $contacts = Contact::first();

        // Detalhes do projeto
        $project = Project::where('demo', $id)->first() ?? null;
        $skillsJson = json_decode(file_get_contents('storage/json/languagens_and_frameworks.json'), true);

        if (!is_null($project)) {
            return view('site.details', [
                'customization' => fn($config, $else = null) => $this->search($config, $else),
                'infos' => $infos,
                'contacts' => $contacts,
                'project' => $project,
                'skillsJson' => $skillsJson
            ]);
        } else return redirect()->route('index');
    }

    // Procurar configuração
    private function search($config, $else = null)
    {
        $customizations = Customization::where('config', $config);
        $customization = $customizations->first();

        if ($customizations->count() > 0) return $customization->value;
        else return $else;
    }
}
