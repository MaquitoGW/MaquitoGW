@extends('layouts.admin')
@section('title', 'Editar Experiencia')

@section('content')
    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Informacoes profissionais</span>
                <h1 class="admin-title">Editar experiencia</h1>
                <p class="admin-subtitle">Atualize os dados profissionais exibidos no template minimalista.</p>
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

        <form action="{{ route('experiences.update', $experience) }}" method="POST" class="admin-card admin-form">
            @csrf

            <div class="form-grid-admin">
                <div class="admin-field">
                    <label for="position" class="admin-label">Cargo *</label>
                    <input type="text" id="position" name="position" value="{{ old('position', $experience->position) }}" class="admin-input" required>
                    @error('position') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-field">
                    <label for="company" class="admin-label">Empresa *</label>
                    <input type="text" id="company" name="company" value="{{ old('company', $experience->company) }}" class="admin-input" required>
                    @error('company') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-field">
                    <label for="start_date" class="admin-label">Data de inicio *</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $experience->start_date->format('Y-m-d')) }}" class="admin-input" required>
                    @error('start_date') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="admin-field">
                    <label for="end_date" class="admin-label">Data de termino</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $experience->end_date?->format('Y-m-d')) }}" class="admin-input">
                    @error('end_date') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="admin-field">
                <label for="location" class="admin-label">Localizacao</label>
                <input type="text" id="location" name="location" value="{{ old('location', $experience->location) }}" class="admin-input">
                @error('location') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="admin-field">
                <label for="description" class="admin-label">Descricao</label>
                <textarea id="description" name="description" rows="5" class="admin-input">{{ old('description', $experience->description) }}</textarea>
                @error('description') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <label class="admin-check">
                <input type="checkbox" id="current" name="current" value="1" @checked(old('current', $experience->current))>
                <span>Emprego atual</span>
            </label>

            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fas fa-save"></i> Atualizar experiencia
                </button>
                <a href="{{ route('experiences') }}" class="admin-btn">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
