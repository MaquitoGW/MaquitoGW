@extends('layouts.admin')
@section('title', 'Adicionar Experiencia')

@section('content')
    @php
        $languageSettings = $languageSettings ?? ['multiple' => false, 'auto' => false];
        $skillCatalog = collect($skillsJson ?? [])->flatMap(fn($group) => $group);
    @endphp
    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Informacoes profissionais</span>
                <h1 class="admin-title">Adicionar experiencia</h1>
                <p class="admin-subtitle">Cadastre cargo, empresa, periodo e descricao para exibir no template minimalista.</p>
            </div>
            <a href="{{ route('experiences') }}" class="admin-btn">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>

        @if ($errors->any())
            <div class="admin-alert admin-alert-error">
                <strong>Verifique os campos abaixo:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('experiences.add') }}" method="POST" class="admin-card admin-form">
            @csrf

            <div class="form-grid-admin">
                <div class="admin-field">
                    <label for="position" class="admin-label">Cargo *</label>
                    <input type="text" id="position" name="position" value="{{ old('position') }}" class="admin-input" placeholder="ex: Senior Frontend Developer" required>
                    @error('position') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-field">
                    <label for="company" class="admin-label">Empresa *</label>
                    <input type="text" id="company" name="company" value="{{ old('company') }}" class="admin-input" placeholder="ex: Tech Innovations Inc." required>
                    @error('company') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-field">
                    <label for="start_date" class="admin-label">Data de inicio *</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="admin-input" required>
                    @error('start_date') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-field">
                    <label for="end_date" class="admin-label">Data de termino</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="admin-input">
                    @error('end_date') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="admin-field">
                <label for="location" class="admin-label">Localizacao</label>
                <input type="text" id="location" name="location" value="{{ old('location') }}" class="admin-input" placeholder="ex: Sao Paulo, BR">
                @error('location') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="admin-field">
                <label for="description" class="admin-label">Descricao</label>
                <textarea id="description" name="description" rows="5" class="admin-input" placeholder="Descreva responsabilidades, resultados e tecnologias utilizadas">{{ old('description') }}</textarea>
                @error('description') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            @if ($languageSettings['multiple'])
                <div class="admin-field">
                    <label for="position_en" class="admin-label">Cargo em ingles</label>
                    <input type="text" id="position_en" name="position_en" value="{{ old('position_en') }}" class="admin-input" placeholder="ex: Senior Frontend Developer">
                    @error('position_en') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-field">
                    <label for="description_en" class="admin-label">Descricao em ingles</label>
                    <textarea id="description_en" name="description_en" rows="5" class="admin-input" placeholder="Responsibilities, achievements and technologies used">{{ old('description_en') }}</textarea>
                    @error('description_en') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            @endif

            <div class="admin-field">
                <label for="promotions" class="admin-label">Evolucoes / promocoes no cargo</label>
                <textarea id="promotions" name="promotions" rows="4" class="admin-input" placeholder="Uma evolucao por linha">{{ old('promotions') }}</textarea>
                @error('promotions') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            @if ($languageSettings['multiple'])
                <div class="admin-field">
                    <label for="promotions_en" class="admin-label">Evolucoes / promocoes em ingles</label>
                    <textarea id="promotions_en" name="promotions_en" rows="4" class="admin-input" placeholder="One promotion per line">{{ old('promotions_en') }}</textarea>
                    @error('promotions_en') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            @endif

            <div class="admin-field">
                <label class="admin-label">Habilidades usadas</label>
                <ul class="list-checkbox">
                    @forelse ($skills as $skill)
                        @php $catalogSkill = $skillCatalog[$skill->code] ?? ['name' => $skill->code, 'icon' => 'fa-solid fa-code']; @endphp
                        <li>
                            <label>
                                <input type="checkbox" name="skills[]" value="{{ $skill->code }}" @checked(in_array($skill->code, old('skills', [])))>
                                <i class="{{ $catalogSkill['icon'] }}"></i>
                                {{ $catalogSkill['name'] }}
                            </label>
                        </li>
                    @empty
                        <p class="admin-subtitle">Nenhuma habilidade cadastrada ainda.</p>
                    @endforelse
                </ul>
                @error('skills') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <label class="admin-check">
                <input type="checkbox" id="current" name="current" value="1" @checked(old('current'))>
                <span>Emprego atual</span>
            </label>
            @error('current') <span class="form-error">{{ $message }}</span> @enderror

            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fas fa-plus"></i> Adicionar experiencia
                </button>
                <a href="{{ route('experiences') }}" class="admin-btn">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
