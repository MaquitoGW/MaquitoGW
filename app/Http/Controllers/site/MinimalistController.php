<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Info;
use App\Models\Contact;
use App\Models\Customization;
use App\Models\Skill;
use App\Support\SkillCatalog;
use Illuminate\Http\Request;

class MinimalistController extends Controller
{
    public function index()
    {
        // Pegar informações
        $infos = [
            'name' => Info::where('info', 'name')->first()->description ?? 'Your Name',
            'fullname' => Info::where('info', 'name')->first()->description ?? 'Your Full Name',
            'position' => Info::where('info', 'position')->first()->description ?? 'Your Position',
            'description' => Info::where('info', 'description')->first()->description ?? 'Your description here',
        ];

        // Pegar contatos
        $contactModel = Contact::first();
        $contacts = [
            'instagram' => $contactModel->instagram ?? null,
            'github' => $contactModel->github ?? null,
            'linkedin' => $contactModel->linkedin ?? null,
            'twitter' => $contactModel->twitter ?? null,
            'email_personal' => $contactModel->email_personal ?? null,
            'email_business' => $contactModel->email_business ?? null,
            'tel' => $contactModel->tel ?? null,
            'csv' => $contactModel->csv ?? null,
        ];

        // Pegar projetos
        $projects = Project::where('active', true)->get();
        $skills = Skill::orderBy('year', 'asc')->get();
        $skillsJson = SkillCatalog::make()->grouped();

        // Pegar experiências profissionais
        $experiences = Experience::where('user_id', 1)->orderBy('position_order')->get();

        // Pegar customizações
        $customization = fn($key, $default = null) => Customization::where('config', $key)->first()?->value ?? $default;

        // Recuperar uma quote aleatória
        $quote = "Seu futuro é brilhante";
        $author = "Seu Nome";

        // Função para buscar código customizado
        $search = fn($config, $default = null) => Customization::where('config', $config)->first()?->value ?? $default;

        return view('templates.minimalist', [
            'infos' => $infos,
            'contacts' => $contacts,
            'projects' => $projects,
            'experiences' => $experiences,
            'skills' => $skills,
            'skillsJson' => $skillsJson,
            'customization' => $customization,
            'quote' => $quote,
            'author' => $author,
            'search' => $search,
        ]);
    }
}
