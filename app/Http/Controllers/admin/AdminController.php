<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Customization;
use App\Models\Info;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        return view('admin.dashboard', [
            'customization' => fn($config, $else = null) => $this->search($config, $else)
        ]);
    }

    // Users 

    public function users()
    {
        // Obter usuarios
        $users = User::get();

        return view('admin.users', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
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
            'customization' => fn($config, $else = null) => $this->search($config, $else),
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
            'customization' => fn($config, $else = null) => $this->search($config, $else),
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
            $addInfo->position = $request->position;
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
            'position' => $request->position,
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

        $addContacts->instagram = $request->instagram ?? null;
        $addContacts->twitter = $request->twitter ?? null;
        $addContacts->github = $request->github ?? null;
        $addContacts->linkedin = $request->linkedin ?? null;
        $addContacts->email_personal = $request->email_personal ?? null;
        $addContacts->email_business = $request->email_business ?? null;
        $addContacts->tel = $request->tel ?? null;

        // Salvar curriculo 
        if ($request->hasFile("csv") && $request->file("csv")->isValid()) {
            $csv = $request->file("csv");
            $extension = $csv->extension();
            $csvName = md5($csv->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $path = '/storage/CSV/';
            $csv->move(public_path($path), $csvName);

            $addContacts->csv = $request->csv;
        }

        $addContacts->save();
        return back()->with('success', 'Informações de contato adicionada com sucesso');
    }

    // Atualizar
    public function updateContacts(Request $request)
    {
        // Salvar curriculo 
        $contacts = Contact::where('id', $request->id);
        if ($request->hasFile("csv") && $request->file("csv")->isValid()) {
            $contactsGET = $contacts->first();
            File::delete(public_path($contactsGET->csv));

            $csv = $request->file("csv");
            $extension = $csv->extension();
            $csvName = md5($csv->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $path = '/storage/csv/';
            $csvNamePath = $path . $csvName;
            $csv->move(public_path($path), $csvName);
        } else $csvNamePath = null;

        // Atualizar o registro diretamente
        $contacts->update([
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'github' => $request->github,
            'linkedin' => $request->linkedin,
            'email_personal' => $request->email_personal,
            'email_business' => $request->email_business,
            'tel' => $request->tel,
            'csv' => $csvNamePath
        ]);

        return back()->with('success', 'Dados atualizados com sucesso');
    }

    // PERSONALIZAÇÂO
    public function customization()
    {
        return view('admin.customization', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'search' => fn($config, $else = null) => $this->search($config, $else)
        ]);
    }

    // PERSONALIZAÇÂO UPDATE
    public function updateCustomization(Request $request)
    {
        // Instanciar uma nova customização
        $customization = new Customization();
        // Ignorar TOKEN e faz o Loop das arrays
        foreach ($request->except('_token') as $config => $value) {
            // Verifica se existe e atualiza
            $check = Customization::where('config', $config);
            if ($check->count() > 0) {
                // Atualizar configuração
                $get = $check->first();
                Customization::where('id', $get->id)->update([
                    'value' => $value
                ]);
            } else {
                // Criar configuração
                $customization->config = $config;
                $customization->value = $value;

                $customization->save();
            }
        }
        return back()->with('success', 'Configurações atualizadas com sucesso');
    }

    public function updateImagesCustomization(Request $request)
    {
        // Instanciar uma nova customização
        $customization = new Customization();
        // Iterar sobre as configurações enviadas, exceto o _token
        foreach ($request->except('_token') as $config => $e) {
            // Verificar se é um arquivo de imagem válido
            if ($request->hasFile($config) && $request->file($config)->isValid()) {
                // Processar a imagem
                $image = $request->file($config);
                $extension = $image->extension();
                $imageName = md5($image->getClientOriginalName() . strtotime("now")) . "." . $extension;

                // Mover a imagem para a pasta de eventos
                $path = '/storage/images/';
                $image->move(public_path($path), $imageName);

                // Atualizar o valor com o caminho da imagem
                $pathAdded = $path . $imageName;

                $check = Customization::where('config', $config);
                if ($check->count() > 0) {
                    // Atualizar configuração
                    $get = $check->first();
                    Customization::where('id', $get->id)->update([
                        'value' => $pathAdded
                    ]);
                } else {
                    // Criar configuração
                    $customization->config = $config;
                    $customization->value = $pathAdded;

                    $customization->save();
                }
            }
        }

        return back()->with('success', 'Imagens atualizadas com sucesso');
    }

    // PROJECTS
    public function projects()
    {
        $projects = Project::get();
        return view('admin.projects', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            "projects" => $projects
        ]);
    }

    public function newProject()
    {
        // Obter todas as habilidades
        $skills = Skill::get();

        // Obter habilidades para o selected
        $skillsJson = json_decode(file_get_contents('storage/json/languagens_and_frameworks.json'), true);

        return view('admin.projectsNew', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'skillsJson' => $skillsJson,
            'skills' => $skills
        ]);
    }

    public function addProject(Request $e)
    {
        // Salvar informnações no BD
        $project = new Project();

        $project->name = $e->name;
        $project->preview = $e->preview;
        $project->description = $e->description;
        $project->demo = md5($e->name . rand(11111111, 99999999) . strtotime('now'));
        $project->github = $e->github;

        // check skills
        if (empty($e->skills)) return redirect()->back()->with('err', 'Adicione pelo menos uma habilidade.');
        else $project->skills = json_encode($e->skills, true);

        // Salvar imagens
        $images = [];
        foreach ($e['images'] as $key => $value) {

            $image_base64 = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $value);
            $image_binary = base64_decode($image_base64);
            $imageName = 'image_' . md5('images' . $key . strtotime('now')) . '.png';
            file_put_contents(public_path('storage/images/') . $imageName, $image_binary); // salva no diretorio public

            // Salvar path
            $images[] = 'storage/images/' . $imageName;
        }
        $project->images = json_encode($images, true);

        // Verificar se há um vídeo e se o arquivo é válido
        if ($e->hasFile('videos') && $e->file('videos')->isValid()) {
            $eVideo = $e->file('videos');
            $videoName = md5($eVideo->getClientOriginalName() . strtotime("now")) . "." . $eVideo->getClientOriginalExtension(); // Gera um nome único com MD5
            $eVideo->move(public_path('storage/videos/'), $videoName);

            $project->videos = 'storage/videos/' . $videoName;
        } else $project->videos = null;

        // Lógica para salvar o arquivo ZIP
        if ($e->hasFile('project') && $e->file('project')->isValid()) {
            $zipFile = $e->file('project');
            $zipFileName = md5($zipFile->getClientOriginalName() . strtotime("now")) . '.' . $zipFile->getClientOriginalExtension();

            // Pastas temporarias
            $tempDir = storage_path('app/temp/');
            $tempPath = $tempDir . $zipFileName;
            $zipFile->move($tempDir, $zipFileName);
            $extractPath = storage_path('app/temp/unzipped/' . $project->demo);

            $zip = new ZipArchive;
            if ($zip->open($tempPath) === true) {
                $zip->extractTo($extractPath);
                $zip->close();

                $mainDemoPath = $extractPath . '/' . pathinfo($zipFile->getClientOriginalName(), PATHINFO_FILENAME);
                if (is_dir($mainDemoPath)) {
                    File::moveDirectory($mainDemoPath, public_path('demo/' . $project->demo));
                }
                File::deleteDirectory($tempDir);
            } else {
                return redirect()->back()->withErrors(['project' => 'Erro ao descompactar o arquivo.']);
            }
        }

        // Salvar e voltar
        $project->save();
        return redirect(route('projects'))->with('success', 'Projeto adicionada com sucesso');
    }

    public function editProject($uuid)
    {
        // Obter todas as habilidades
        $skills = Skill::get();
        $project = Project::where("demo", $uuid)->first();
        Session::put("uuid", $uuid);

        // Obter habilidades para o selected
        $skillsJson = json_decode(file_get_contents('storage/json/languagens_and_frameworks.json'), true);
        $skillsChecked = json_decode($project->skills, true);

        $images = [];
        $dataImage = json_decode($project->images, true);
        if ($dataImage) {
            foreach ($dataImage as $image) {
                if (file_exists($image)) {
                    $data = file_get_contents($image);
                    $images[] = "data:image/jpeg;base64," . base64_encode($data);
                }
            }
        }

        return view('admin.projectsEdit', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'project' => $project,
            'images' => $images,
            'skillsChecked' => $skillsChecked,
            'skillsJson' => $skillsJson,
            'skills' => $skills
        ]);
    }

    public function updateProject(Request $e, $uuid)
    {
        // Encontrar o projeto pelo ID
        $project = Project::where("demo", $uuid)->first();

        if (!$project) {
            return redirect()->back()->withErrors(['project' => 'Projeto não encontrado.']);
        }

        // Atualizar os dados do projeto
        $project->name = $e->name;
        $project->preview = $e->preview;
        $project->description = $e->description;
        $project->github = $e->github;
        $project->skills = json_encode($e->skills, true);

        // Atualizar imagens
        $images = [];
        if ($e->has('images')) {
            // Delete images
            $dataImage = json_decode($project->images, true);
            if ($dataImage) {
                foreach ($dataImage as $image) {
                    if (file_exists($image)) unlink(public_path($image));
                }
            }

            // Atualizar
            foreach ($e['images'] as $key => $value) {
                $image_base64 = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $value);
                $image_binary = base64_decode($image_base64);
                $imageName = 'image_' . md5('images' . $key . strtotime('now')) . '.png';
                file_put_contents(public_path('storage/images/') . $imageName, $image_binary);
                $images[] = 'storage/images/' . $imageName;
            }
            $project->images = json_encode($images, true);
        }

        // Atualizar vídeo
        if ($e->hasFile('videos') && $e->file('videos')->isValid()) {
            // Delete video
            if (file_exists($project->videos)) unlink(public_path($project->videos));

            $eVideo = $e->file('videos');
            $videoName = md5($eVideo->getClientOriginalName() . strtotime("now")) . "." . $eVideo->getClientOriginalExtension();
            $eVideo->move(public_path('storage/videos/'), $videoName);
            $project->videos = 'storage/videos/' . $videoName;
        }

        // Salvar e voltar
        $project->save();
        return back()->with('success', 'O projeto ' . $e->name . ' foi atualizado com sucesso.');
    }

    public function deleteProject($uuid)
    {
        // Encontrar o projeto pelo ID
        $project = Project::where("demo", $uuid)->first();

        if ($project) {
            // Delete images
            $dataImage = json_decode($project->images, true);
            if ($dataImage) {
                foreach ($dataImage as $image) {
                    if (file_exists($image)) unlink(public_path($image));
                }
            }

            // Delete video
            if (file_exists($project->videos)) unlink(public_path($project->videos));

            // Delete diretorio
            $diretorio = public_path("demo/" . $uuid);
            if (File::exists($diretorio)) {
                File::deleteDirectory($diretorio);
            }

            $name = $project->name;
            $project->delete();
            return redirect()->route("projects")->with('success', "O projeto " . $name . " foi apagado com sucesso");
        } else return redirect()->route("projects")->with('success', "Ocorreu um erro ao apagar o projeto");
    }

    public function filemanagerProjects(Request $e)
    {
        // Gerar grenciador de arquivos
        if (!is_null(session('uuid'))) {
            define('FM_EMBED', true);
            define('CSFR_TOKEN', csrf_token());
            define("FM_ROOT_URL", '/demo/' . session('uuid'));
            define('FM_ROOT_PATH', public_path('demo/' . session('uuid')));
            require storage_path('app/include/filemanager.php');
        } else return redirect()->route("projects")->with('success', "A requisição enviada é inválida");
    }

    // Procurar configuração
    private function search($config, $else = null)
    {
        $customizations = Customization::where('config', $config);
        $customization = $customizations->first();

        if ($customizations->count() > 0) return $customization->value;
        else return $else;
    }

    public function settings()
    {
        return view("admin.settings", [
            'customization' => fn($config, $else = null) => $this->search($config, $else)
        ]);
    }

    public function settingsUpdate(Request $request)
    {
        $env = File::get(base_path('.env'));

        $env = str_replace('APP_TITLE=' . env('APP_TITLE'), 'APP_TITLE="' . $request->input('app-name') . '"', $env);
        $env = str_replace('APP_ENV=' . env('APP_ENV'), 'APP_ENV=' . $request->input('app-env'), $env);
        $env = str_replace('APP_DEBUG=' . env('APP_DEBUG'), 'APP_DEBUG=' . $request->input('app-debug'), $env);
        $env = str_replace('APP_TIMEZONE=' . env('APP_TIMEZONE'), 'APP_TIMEZONE=' . $request->input('app-timezone'), $env);

        $env = str_replace('APP_FAKER_LOCALE=' . env('APP_FAKER_LOCALE'), 'APP_FAKER_LOCALE=' . $request->input('app-faker-locale'), $env);
        $language = explode("_", $request->input('app-faker-locale'))[0];

        $env = str_replace('APP_LOCALE=' . env('APP_LOCALE'), 'APP_LOCALE=' . $language, $env);
        $env = str_replace('APP_FALLBACK_LOCALE=' . env('APP_FALLBACK_LOCALE'), 'APP_FALLBACK_LOCALE=' . $language, $env);
        $env = str_replace('MULTIPLE_LANGUAGES=' . env('MULTIPLE_LANGUAGES'), 'MULTIPLE_LANGUAGES=' . $request->input('multiple-languages'), $env);

        File::put(base_path('.env'), $env);

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}
