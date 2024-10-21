<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Apis\McsrvstatController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\functions\UpdateController;
use App\Models\Contact;
use App\Models\Info;
use App\Models\Skill;
use App\Models\User;
use App\Models\World;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
        return view('admin.dashboard', []);
    }

    // Users 

    public function users()
    {
        // Obter usuarios
        $users = User::get();

        return view('admin.users', [
            'users' => $users
        ]);
    }

    // HABILIDADES
    public function skills()
    {
        // Obter todas as habilidades
        $skills = Skill::get();

        // Obter habilidades para o selected
        $skillsJson = json_decode(file_get_contents('storage/json/languagens_and_frameworks.json'), true);

        return view('admin.skills', [
            'skillsJson' => $skillsJson,
            'skills' => $skills
        ]);
    }

    // Adicionar uma nova habilidade
    public function skillAdd(Request $request)
    {
        // Verificar se já existe habilidade
        if (!Skill::where('code', $request->skill)->count() > 0) {
            $addSkill = new Skill();

            $addSkill->code = $request->skill;
            $addSkill->year = $request->year;

            $addSkill->save();
            return back()->with('success', 'Habilidade adicionada com sucesso');
        } else return back()->with('err', 'Habilidade já adicionada');
    }

    // Apagar habilidade
    public function skillDelete($code)
    {
        Skill::where('code', $code)->delete();
        return back()->with('success', 'Habilidade deletada com sucesso');
    }

    // INFORMACOES DO USUARIO
    public function info()
    {
        // obter informações
        $infos = Info::get();
        $route = explode('.', Route::currentRouteName());
        $InfoLanguages = Info::count();
        $contacts = Contact::first();
        $contactsCheck = Contact::count();

        // Redirecionar se tentar adicionar denovo
        if (isset($route[1])) {
            foreach ($infos as $item) {
                if ($route[2] == $item->language) {
                    if ($item->language == 'en_US' || $item->language == 'pt_BR') return redirect(route('info'))->with('err', 'Informações já adicionada');
                }
            }
        }

        return view('admin.info', [
            'infos' => $infos,
            'route' => $route,
            'infoLanguages' => $InfoLanguages,
            'contacts' => $contacts,
            'contactsCheck' => $contactsCheck
        ]);
    }

    // Adicionar informações
    public function addInfo(Request $request)
    {
        // Verificar se já existe 
        if (!Info::where('language', $request->language)->count() > 0) {
            $addInfo = new Info();

            $addInfo->language = $request->language;
            $addInfo->name = $request->name;
            $addInfo->fullname = $request->fullname;
            $addInfo->description = $request->description;

            $addInfo->save();
            return redirect(route('info'))->with('success', 'Informações adicionada com sucesso');
        } else return redirect(route('info'))->with('err', 'Informações já adicionada');
    }

    // Atualizar as informações
    public function updateInfo(Request $request)
    {
        // Atualizar o registro diretamente
        Info::where('id', $request->id)->update([
            'name' => $request->name,
            'fullname' => $request->fullname,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Dados atualizados com sucesso');
    }

    // CONTATOS DO USUARIO
    // Adicionar 
    public function addContacts(Request $request)
    {
        $addContacts = new Contact();

        $addContacts->instagram = $request->instagram;
        $addContacts->twitter = $request->twitter;
        $addContacts->github = $request->github;
        $addContacts->linkedin = $request->linkedin;
        $addContacts->email_personal = $request->email_personal;
        $addContacts->email_business = $request->email_business;
        $addContacts->tel = $request->tel;

        $addContacts->save();
        return redirect(route('info'))->with('success', 'Informações de contato adicionada com sucesso');
    }

    // Atualizar
    public function updateContacts(Request $request)
    {
        // Atualizar o registro diretamente
        Contact::where('id', $request->id)->update([
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'github' => $request->github,
            'linkedin' => $request->linkedin,
            'email_personal' => $request->email_personal,
            'email_business' => $request->email_business,
            'tel' => $request->tel
        ]);

        return back()->with('success', 'Dados atualizados com sucesso');
    }

    // PERSONALIZAÇÂO
    public function customization() {
        return view('admin.customization',[
            
        ]);
    }
}
