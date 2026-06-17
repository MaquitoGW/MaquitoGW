<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\AppSetting;
use App\Models\Customization;
use App\Models\Experience;
use App\Models\Info;
use App\Models\Project;
use App\Models\SiteVisit;
use App\Models\Skill;
use App\Support\SkillCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $this->trackVisit($request, 'site');

        if ($this->setting('PORTFOLIO_ENABLED', env('PORTFOLIO_ENABLED', 'true')) === 'false') {
            return response()->view('site.not_configured', [
                'portfolioDisabled' => true,
                'isNotConfigurad' => true,
            ], 200);
        }

        if (env("MULTIPLE_LANGUAGES") == 1) {
            $languageUserGET = str_replace("-", "_", App::getLocale());

            $language = match ($languageUserGET) {
                'en_US', 'pt_BR' => $languageUserGET,
                default => env('APP_FAKER_LOCALE'),
            };
        } else {
            $language = env('APP_FAKER_LOCALE');
        }

        $infos = Info::where('language', $language)->first();
        $contacts = Contact::first();

        // fallback global de sistema não configurado
        if (!$infos || !$contacts) {
            return response()->view('site.not_configured', ['isNotConfigurad' => true], 200);
        }

        $projects = Project::all();
        $experiences = Experience::orderBy('position_order')
            ->orderByDesc('start_date')
            ->get();

        $skills = Skill::orderBy('year', 'asc')->get();

        $skillsJson = SkillCatalog::make()->grouped();

        $skin = $this->search('site_skin', 'default');

        $skin = ($skin === 'default' || empty($skin))
            ? 'site.index'
            : 'templates.' . $skin;

        return view($skin, [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'search' => fn($config, $else = null) => $this->search($config, $else),

            'infos' => $infos,
            'contacts' => $contacts,
            'projects' => $projects,
            'experiences' => $experiences,
            'skills' => $skills,
            'skillsJson' => $skillsJson,
        ]);
    }

    public function details($id)
    {
        $languageUserGET = str_replace("-", "_", App::getLocale());

        $language = in_array($languageUserGET, ['pt_BR', 'en_US'])
            ? $languageUserGET
            : env('APP_FAKER_LOCALE');

        $infos = Info::where('language', $language)->first();
        $contacts = Contact::first();

        $project = Project::where('demo', $id)->first();

        if (!$project) {
            return redirect()->route('index');
        }

        $skillsJson = SkillCatalog::make()->grouped();

        return view('site.details', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),

            'infos' => $infos,
            'contacts' => $contacts,
            'project' => $project,
            'skillsJson' => $skillsJson
        ]);
    }

    // busca segura de customização
    private function search($config, $else = null)
    {
        $customization = Customization::where('config', $config)->first();

        if (!$customization) {
            return $else;
        }

        return $customization->encode
            ? base64_decode($customization->value)
            : $customization->value;
    }

    private function setting(string $key, ?string $fallback = null): ?string
    {
        try {
            return AppSetting::where('key', $key)->value('value') ?? $fallback;
        } catch (\Throwable) {
            return $fallback;
        }
    }

    // tracking seguro
    private function trackVisit(Request $request, string $type): void
    {
        try {
            SiteVisit::create([
                'type' => $type,
                'path' => $request->path(),
                'ip_hash' => hash('sha256', (string) $request->ip()),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
            ]);
        } catch (\Throwable $e) {
            // nunca quebra o site por tracking
        }
    }
}
