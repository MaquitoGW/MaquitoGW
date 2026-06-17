<?php

namespace App\Http\Controllers\admin;

use App\Models\Experience;
use App\Models\Skill;
use App\Support\SkillCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends AdminController
{
    public function index()
    {
        $experiences = Experience::query()
            ->orderBy('position_order')
            ->orderByDesc('start_date')
            ->get();

        return view('admin.experiences', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'experiences' => $experiences,
        ]);
    }

    public function create()
    {
        return view('admin.experiencesCreate', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'skills' => Skill::orderBy('year')->get(),
            'skillsJson' => SkillCatalog::make()->grouped(),
            'languageSettings' => $this->languageSettings(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $validated['position_order'] = (int) Experience::query()->max('position_order') + 1;
        $validated['user_id'] = Auth::id() ?? 1;

        Experience::create($validated);

        return redirect()->route('experiences')->with('success', 'Experiencia adicionada com sucesso!');
    }

    public function edit(Experience $experience)
    {
        return view('admin.experiencesEdit', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'experience' => $experience,
            'skills' => Skill::orderBy('year')->get(),
            'skillsJson' => SkillCatalog::make()->grouped(),
            'languageSettings' => $this->languageSettings(),
        ]);
    }

    public function update(Request $request, Experience $experience)
    {
        $experience->update($this->validatedData($request));

        return redirect()->route('experiences')->with('success', 'Experiencia atualizada com sucesso!');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();

        return redirect()->route('experiences')->with('success', 'Experiencia deletada com sucesso!');
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'position_en' => 'nullable|string|max:255',
            'company' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'promotions' => 'nullable|string',
            'promotions_en' => 'nullable|string',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:50',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'current' => 'nullable',
        ]);

        $validated['current'] = $request->has('current');
        $validated['promotions'] = $this->linesToArray($request->input('promotions'));
        $validated['promotions_en'] = $this->linesToArray($request->input('promotions_en'));
        $validated['skills'] = $request->input('skills', []);

        if ($validated['current']) {
            $validated['end_date'] = null;
        }

        if (!$this->languageSettings()['multiple']) {
            $validated['position_en'] = null;
            $validated['description_en'] = null;
            $validated['promotions_en'] = [];
        }

        return $validated;
    }

    private function linesToArray(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function languageSettings(): array
    {
        return [
            'multiple' => env('MULTIPLE_LANGUAGES') == 1,
            'auto' => env('PROJECT_TRANSLATION_ENABLED', false) == true,
        ];
    }
}
