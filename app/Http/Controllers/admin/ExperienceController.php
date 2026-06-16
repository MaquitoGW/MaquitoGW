<?php

namespace App\Http\Controllers\admin;

use App\Models\Experience;
use Illuminate\Http\Request;

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
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'current' => 'nullable',
        ]);

        $validated['position_order'] = (int) Experience::query()->max('position_order') + 1;
        $validated['current'] = $request->has('current');
        if ($validated['current']) {
            $validated['end_date'] = null;
        }

        Experience::create($validated);

        return redirect()->route('experiences')->with('success', 'Experiência adicionada com sucesso!');
    }

    public function edit(Experience $experience)
    {
        return view('admin.experiencesEdit', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'experience' => $experience,
        ]);
    }

    public function update(Request $request, Experience $experience)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'current' => 'nullable',
        ]);

        $validated['current'] = $request->has('current');
        if ($validated['current']) {
            $validated['end_date'] = null;
        }

        $experience->update($validated);

        return redirect()->route('experiences')->with('success', 'Experiência atualizada com sucesso!');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();

        return redirect()->route('experiences')->with('success', 'Experiência deletada com sucesso!');
    }
}
