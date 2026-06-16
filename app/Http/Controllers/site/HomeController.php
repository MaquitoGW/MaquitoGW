<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Customization;
use App\Models\Experience;
use App\Models\Info;
use App\Models\Project;
use App\Models\SiteVisit;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{
    // Função inicial
    public function index(Request $request)
    {
        $this->trackVisit($request, 'site');

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
        $experiences = Experience::orderBy('position_order')->orderByDesc('start_date')->get();

        $skills = Skill::orderBy('year', 'asc')->get();
        $skillsJson = json_decode(file_get_contents('storage/json/languagens_and_frameworks.json'), true);

        // verificar se existe dados 

        if (!$infos || !$contacts) abort(404);

        $skin = $this->search('site_skin', 'default');
        if ($skin == 'default' || empty($skin)) {
            $skin = 'site.index';
        } else {
            $skin = 'templates.' . $skin;
        }

        return view($skin, [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'infos' => $infos,
            'contacts' => $contacts,
            'projects' => $projects,
            'experiences' => $experiences,
            'skills' => $skills,
            'skillsJson' => $skillsJson,
            'search' => fn($config, $else = null) => $this->search($config, $else)
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

        if ($customizations->count() > 0) {
            if ($customization->encode) {
                return base64_decode($customization->value);
            } else {
                return $customization->value;
            }
        } else return $else;
    }

    private function trackVisit(Request $request, string $type): void
    {
        SiteVisit::create([
            'type' => $type,
            'path' => $request->path(),
            'ip_hash' => hash('sha256', (string) $request->ip()),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);
    }
}
