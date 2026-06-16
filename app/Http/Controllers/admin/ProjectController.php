<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Skill;
use App\Services\ProjectTranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use ZipArchive;

class ProjectController extends AdminController
{
    public function index()
    {
        $skillsJson = json_decode(file_get_contents('storage/json/languagens_and_frameworks.json'), true);
        $allSkills = array_merge(
            $skillsJson['languages'] ?? [],
            $skillsJson['frameworks'] ?? [],
            $skillsJson['software_skills'] ?? []
        );

        $skillsMap = [];
        foreach ($allSkills as $code => $skill) {
            $skillsMap[$code] = $skill['name'];
        }

        $projects = Project::all()->map(function ($project) use ($skillsMap) {
            $project->preview = strlen($project->preview) > 60
                ? substr($project->preview, 0, 60) . '...'
                : $project->preview;

            $skillsCodes = json_decode($project->skills, true) ?: [];
            $project->skills = array_map(fn($code) => $skillsMap[$code] ?? $code, $skillsCodes);

            $images = json_decode($project->images, true) ?: [];
            $project->thumbnail = "/" . ltrim($images[0], '/') ?? 'https://via.placeholder.com/400x200?text=No+Image';

            return $project;
        });

        return view('admin.projects', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'projects' => $projects->toArray()
        ]);
    }

    public function create()
    {
        $skills = Skill::get();
        $skillsJson = json_decode(file_get_contents('storage/json/languagens_and_frameworks.json'), true);

        return view('admin.projectsNew', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'skillsJson' => $skillsJson,
            'skills' => $skills,
            'projectLanguageSettings' => $this->projectLanguageSettings(),
        ]);
    }

    public function store(Request $e)
    {
        $project = new Project();
        $project->name = $e->name;
        $project->preview = $e->preview;
        $project->description = $e->description;
        if ($this->allowsManualProjectTranslation($e)) {
            $project->name_en = $e->name_en;
            $project->preview_en = $e->preview_en;
            $project->description_en = $e->description_en;
        } elseif (!$this->shouldAutoTranslateProject($e)) {
            $project->name_en = null;
            $project->preview_en = null;
            $project->description_en = null;
        }
        $project->demo = md5($e->name . rand(11111111, 99999999) . strtotime('now'));
        $project->github = $e->github;

        if ($this->shouldAutoTranslateProject($e)) {
            $this->fillEnglishFields($project, $e);
        }

        if (empty($e->skills)) return redirect()->back()->with('err', 'Adicione pelo menos uma habilidade.');
        else $project->skills = json_encode($e->skills, true);

        $images = [];
        foreach ($e['images'] as $key => $value) {
            $image_base64 = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $value);
            $image_binary = base64_decode($image_base64);
            $imageName = 'image_' . md5('images' . $key . strtotime('now')) . '.png';
            file_put_contents(public_path('storage/images/') . $imageName, $image_binary);
            $images[] = 'storage/images/' . $imageName;
        }
        $project->images = json_encode($images, true);

        if ($e->hasFile('videos') && $e->file('videos')->isValid()) {
            $eVideo = $e->file('videos');
            $videoName = md5($eVideo->getClientOriginalName() . strtotime("now")) . "." . $eVideo->getClientOriginalExtension();
            $eVideo->move(public_path('storage/videos/'), $videoName);
            $project->videos = 'storage/videos/' . $videoName;
        } else {
            $project->videos = null;
        }

        if ($e->hasFile('project') && $e->file('project')->isValid()) {
            $zipFile = $e->file('project');
            $zipFileName = md5($zipFile->getClientOriginalName() . strtotime("now")) . '.' . $zipFile->getClientOriginalExtension();
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

        $project->save();
        return redirect(route('projects'))->with('success', 'Projeto adicionada com sucesso');
    }

    public function edit($uuid)
    {
        $skills = Skill::get();
        $project = Project::where("demo", $uuid)->first();
        Session::put("uuid", $uuid);

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
            'skills' => $skills,
            'projectLanguageSettings' => $this->projectLanguageSettings(),
        ]);
    }

    public function update(Request $e, $uuid)
    {
        $project = Project::where("demo", $uuid)->first();

        if (!$project) {
            return redirect()->back()->withErrors(['project' => 'Projeto não encontrado.']);
        }

        $project->name = $e->name;
        $project->preview = $e->preview;
        $project->description = $e->description;
        if ($this->projectLanguageSettings()['show']) {
            if ($this->allowsManualProjectTranslation($e)) {
                $project->name_en = $e->name_en;
                $project->preview_en = $e->preview_en;
                $project->description_en = $e->description_en;
            } elseif (!$this->shouldAutoTranslateProject($e)) {
                $project->name_en = null;
                $project->preview_en = null;
                $project->description_en = null;
            }
        }
        $project->github = $e->github;
        $project->skills = json_encode($e->skills, true);

        if ($this->shouldAutoTranslateProject($e)) {
            $this->fillEnglishFields($project, $e);
        }

        $images = [];
        if ($e->has('images')) {
            $dataImage = json_decode($project->images, true);
            if ($dataImage) {
                foreach ($dataImage as $image) {
                    if (file_exists($image)) unlink(public_path($image));
                }
            }

            foreach ($e['images'] as $key => $value) {
                $image_base64 = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $value);
                $image_binary = base64_decode($image_base64);
                $imageName = 'image_' . md5('images' . $key . strtotime('now')) . '.png';
                file_put_contents(public_path('storage/images/') . $imageName, $image_binary);
                $images[] = 'storage/images/' . $imageName;
            }
            $project->images = json_encode($images, true);
        }

        if ($e->hasFile('videos') && $e->file('videos')->isValid()) {
            if (file_exists($project->videos)) unlink(public_path($project->videos));
            $eVideo = $e->file('videos');
            $videoName = md5($eVideo->getClientOriginalName() . strtotime("now")) . "." . $eVideo->getClientOriginalExtension();
            $eVideo->move(public_path('storage/videos/'), $videoName);
            $project->videos = 'storage/videos/' . $videoName;
        }

        $project->save();
        return back()->with('success', 'O projeto ' . $e->name . ' foi atualizado com sucesso.');
    }

    public function destroy($uuid)
    {
        $project = Project::where("demo", $uuid)->first();

        if ($project) {
            $dataImage = json_decode($project->images, true);
            if ($dataImage) {
                foreach ($dataImage as $image) {
                    if (file_exists($image)) unlink(public_path($image));
                }
            }

            if (file_exists($project->videos)) unlink(public_path($project->videos));

            $diretorio = public_path("demo/" . $uuid);
            if (File::exists($diretorio)) {
                File::deleteDirectory($diretorio);
            }

            $name = $project->name;
            $project->delete();
            return redirect()->route("projects")->with('success', "O projeto " . $name . " foi apagado com sucesso");
        } else {
            return redirect()->route("projects")->with('success', "Ocorreu um erro ao apagar o projeto");
        }
    }

    public function filemanager(Request $e)
    {
        if (!is_null(session('uuid'))) {
            define('FM_EMBED', true);
            define('CSFR_TOKEN', csrf_token());
            define("FM_ROOT_URL", '/demo/' . session('uuid'));
            define('FM_ROOT_PATH', public_path('demo/' . session('uuid')));
            require storage_path('app/include/filemanager.php');
        } else {
            return redirect()->route("projects")->with('success', "A requisição enviada é inválida");
        }
    }

    public function files(Request $request, string $uuid)
    {
        $project = Project::where('demo', $uuid)->firstOrFail();
        $paths = $this->projectFilePaths($project, $request->query('path'));
        $selectedFile = null;

        if ($request->filled('file')) {
            $selected = $this->resolveProjectFile($project, $request->query('file'));
            if ($selected && $this->isEditableFile($selected['absolute'])) {
                $selectedFile = [
                    'relative' => $selected['relative'],
                    'name' => basename($selected['absolute']),
                    'content' => File::get($selected['absolute']),
                ];
            }
        }

        return view('admin.projectsFiles', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'project' => $project,
            'currentPath' => $paths['relative'],
            'parentPath' => $this->parentRelativePath($paths['relative']),
            'directories' => $this->directoryItems($project, $paths['absolute']),
            'files' => $this->fileItems($project, $paths['absolute']),
            'selectedFile' => $selectedFile,
        ]);
    }

    public function uploadFile(Request $request, string $uuid)
    {
        $project = Project::where('demo', $uuid)->firstOrFail();
        $paths = $this->projectFilePaths($project, $request->input('path'));

        foreach ($request->file('files', []) as $file) {
            if ($file && $file->isValid()) {
                $file->move($paths['absolute'], $this->safeFileName($file->getClientOriginalName()));
            }
        }

        return redirect()->route('projects.files', ['uuid' => $uuid, 'path' => $paths['relative']])->with('success', 'Arquivos enviados com sucesso.');
    }

    public function createFolder(Request $request, string $uuid)
    {
        $project = Project::where('demo', $uuid)->firstOrFail();
        $paths = $this->projectFilePaths($project, $request->input('path'));
        $folder = $this->safeFileName($request->input('folder'));

        if ($folder !== '') {
            File::ensureDirectoryExists($paths['absolute'] . DIRECTORY_SEPARATOR . $folder);
        }

        return redirect()->route('projects.files', ['uuid' => $uuid, 'path' => $paths['relative']])->with('success', 'Pasta criada com sucesso.');
    }

    public function deleteFile(Request $request, string $uuid)
    {
        $project = Project::where('demo', $uuid)->firstOrFail();
        $target = $this->resolveProjectFile($project, $request->input('target'));

        if ($target) {
            is_dir($target['absolute'])
                ? File::deleteDirectory($target['absolute'])
                : File::delete($target['absolute']);
        }

        return redirect()->route('projects.files', ['uuid' => $uuid, 'path' => $request->input('path')])->with('success', 'Item removido com sucesso.');
    }

    public function saveFile(Request $request, string $uuid)
    {
        $project = Project::where('demo', $uuid)->firstOrFail();
        $target = $this->resolveProjectFile($project, $request->input('target'));

        if ($target && is_file($target['absolute']) && $this->isEditableFile($target['absolute'])) {
            File::put($target['absolute'], $request->input('content', ''));
        }

        return redirect()->route('projects.files', ['uuid' => $uuid, 'path' => $request->input('path'), 'file' => $request->input('target')])->with('success', 'Arquivo salvo com sucesso.');
    }

    private function fillEnglishFields(Project $project, Request $request): void
    {
        $translator = app(ProjectTranslationService::class);

        $project->name_en = $translator->translateToEnglish($request->name) ?: $project->name_en;
        $project->preview_en = $translator->translateToEnglish($request->preview) ?: $project->preview_en;
        $project->description_en = $translator->translateHtmlToEnglish($request->description) ?: $project->description_en;
    }

    private function projectLanguageSettings(): array
    {
        $multipleLanguages = env('MULTIPLE_LANGUAGES') == 1;
        $autoTranslation = env('PROJECT_TRANSLATION_ENABLED', false) == true;

        return [
            'multiple' => $multipleLanguages,
            'auto' => $autoTranslation,
            'show' => $multipleLanguages || $autoTranslation,
        ];
    }

    private function shouldAutoTranslateProject(Request $request): bool
    {
        return env('PROJECT_TRANSLATION_ENABLED', false) == true
            && $request->boolean('project_bilingual')
            && $request->boolean('auto_translate');
    }

    private function allowsManualProjectTranslation(Request $request): bool
    {
        return env('MULTIPLE_LANGUAGES') == 1
            && $request->boolean('project_bilingual')
            && !$this->shouldAutoTranslateProject($request);
    }

    private function projectFilePaths(Project $project, ?string $relativePath = null): array
    {
        $root = public_path('demo/' . $project->demo);
        File::ensureDirectoryExists($root);

        $rootReal = realpath($root);
        $relative = trim(str_replace('\\', '/', (string) $relativePath), '/');
        $absolute = $relative === '' ? $rootReal : realpath($rootReal . DIRECTORY_SEPARATOR . $relative);

        if (!$absolute || !str_starts_with(str_replace('\\', '/', $absolute), str_replace('\\', '/', $rootReal)) || !is_dir($absolute)) {
            $absolute = $rootReal;
            $relative = '';
        }

        return ['root' => $rootReal, 'absolute' => $absolute, 'relative' => $relative];
    }

    private function resolveProjectFile(Project $project, ?string $relativePath): ?array
    {
        $root = public_path('demo/' . $project->demo);
        File::ensureDirectoryExists($root);

        $rootReal = realpath($root);
        $relative = trim(str_replace('\\', '/', (string) $relativePath), '/');
        $absolute = realpath($rootReal . DIRECTORY_SEPARATOR . $relative);

        if (!$absolute || !str_starts_with(str_replace('\\', '/', $absolute), str_replace('\\', '/', $rootReal))) {
            return null;
        }

        return ['absolute' => $absolute, 'relative' => $this->relativeFromRoot($project, $absolute)];
    }

    private function directoryItems(Project $project, string $absolutePath): array
    {
        return collect(File::directories($absolutePath))->map(fn($directory) => [
            'name' => basename($directory),
            'relative' => $this->relativeFromRoot($project, $directory),
        ])->sortBy('name')->values()->all();
    }

    private function fileItems(Project $project, string $absolutePath): array
    {
        return collect(File::files($absolutePath))->map(fn($file) => [
            'name' => $file->getFilename(),
            'relative' => $this->relativeFromRoot($project, $file->getPathname()),
            'size' => $this->humanFileSize($file->getSize()),
            'editable' => $this->isEditableFile($file->getPathname()),
            'modified' => date('d/m/Y H:i', $file->getMTime()),
        ])->sortBy('name')->values()->all();
    }

    private function relativeFromRoot(Project $project, string $absolutePath): string
    {
        $root = str_replace('\\', '/', realpath(public_path('demo/' . $project->demo)));
        $path = str_replace('\\', '/', realpath($absolutePath) ?: $absolutePath);

        return trim(Str::after($path, $root), '/');
    }

    private function parentRelativePath(string $relativePath): ?string
    {
        if ($relativePath === '') {
            return null;
        }

        $parent = trim(dirname(str_replace('\\', '/', $relativePath)), '.\\/');

        return $parent === '' ? '' : $parent;
    }

    private function safeFileName(?string $name): string
    {
        $name = basename(trim((string) $name));
        $name = preg_replace('/[^A-Za-z0-9._ -]/', '-', $name);

        return trim((string) $name, '. ');
    }

    private function isEditableFile(string $path): bool
    {
        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), [
            'html', 'htm', 'css', 'js', 'json', 'txt', 'md', 'xml', 'svg',
        ], true);
    }

    private function humanFileSize(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        if ($bytes < 1048576) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return round($bytes / 1048576, 1) . ' MB';
    }
}
