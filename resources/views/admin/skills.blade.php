@extends('layouts.admin')
@section('title', 'Habilidades')
@section('content')
    <div class="top-actions">
        <button action-click="{{ route('skill.new') }}" class="add-skill-btn">
            <i class="fas fa-plus"></i> Adicionar Habilidade
        </button>
    </div>

    @if (Route::currentRouteName() == 'skill.new')
        <form action="{{ route('skill.add') }}" method="post">
            @csrf
            <h3>Adicionar Habilidade</h3>
            <label for="skills">Habilidade</label>
            <select name="skill" id="skills" required>
                <option disabled selected>Selecione uma habilidade</option>
                <optgroup label="Linguagens">
                    @foreach ($skillsJson['languages'] as $code => $language)
                        <option value="{{ $code }}">{{ $language['name'] }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Frameworks">
                    @foreach ($skillsJson['frameworks'] as $code => $framework)
                        <option value="{{ $code }}">{{ $framework['name'] }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Software skill">
                    @foreach ($skillsJson['software_skills'] as $code => $software_skill)
                        <option value="{{ $code }}">{{ $software_skill['name'] }}</option>
                    @endforeach
                </optgroup>
            </select>

            <label for="year">Ano</label>
            <input required type="number" name="year" min="2000" max="2100" placeholder="Year">

            <div class="options">
                <button type="submit">Adicionar</button>
                <a class="success" href="{{ route('skills') }}">Fechar</a>
            </div>
        </form>
    @endif

    <div class="skills-grid">
        @forelse ($skills as $skill)
            @php
                $catalog = collect($skillsJson)->flatMap(fn($group) => $group);
                $language = $catalog[$skill->code] ?? [
                    'name' => $skill->code,
                    'description_pt' => '',
                    'icon' => 'fa-solid fa-code',
                ];
            @endphp
            <div class="skill-card">
                <div class="skill-header">
                    <i class="{{ $language['icon'] }} skill-icon"></i>
                    <span class="skill-year">{{ $skill->year }}</span>
                </div>
                <div class="skill-content">
                    <h3 class="skill-title">{{ $language['name'] }}</h3>
                    <p class="skill-description">{{ $language['description_pt'] ?? '' }}</p>
                </div>
                <div class="skill-footer">
                    <a href="{{ route('skills.delete', $skill->code) }}" class="remove-skill-btn" onclick="return confirm('Remover esta habilidade?')">
                        <i class="fas fa-trash-alt"></i> Remover
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fa-solid fa-star"></i>
                <h3>Nenhuma habilidade cadastrada</h3>
                <p>Adicione habilidades para destacar as tecnologias usadas nos projetos.</p>
                <button action-click="{{ route('skill.new') }}" class="add-skill-btn">
                    <i class="fas fa-plus"></i> Adicionar habilidade
                </button>
            </div>
        @endforelse

    </div>
@endsection
