@extends('layouts.admin')
@section('title', 'Adicionar Link')

@section('content')
    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Meus Links</span>
                <h1 class="admin-title">Adicionar link</h1>
                <p class="admin-subtitle">Crie um novo botão para sua página pública de links.</p>
            </div>
            <a href="{{ route('links') }}" class="admin-btn">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

        <form action="{{ route('links.add') }}" method="POST" class="admin-card">
            @csrf

            <label for="title">Título do link</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Meu portfolio" required>
            @error('title') <p class="alert">{{ $message }}</p> @enderror

            <label for="url">URL do link</label>
            <input type="url" id="url" name="url" value="{{ old('url') }}" placeholder="https://exemplo.com" required>
            @error('url') <p class="alert">{{ $message }}</p> @enderror

            <label for="icon">Ícone</label>
            <input type="text" id="icon" name="icon" value="{{ old('icon') }}" placeholder="github, instagram, website...">
            <p class="admin-list-description">Use nomes comuns como github, instagram, linkedin, youtube, whatsapp, email ou website.</p>
            @error('icon') <p class="alert">{{ $message }}</p> @enderror

            <label for="description">Descrição</label>
            <textarea id="description" name="description" rows="3" placeholder="Descrição curta do link">{{ old('description') }}</textarea>
            @error('description') <p class="alert">{{ $message }}</p> @enderror

            <div class="admin-actions">
                <button type="submit" class="admin-btn-primary">Adicionar link</button>
                <a href="{{ route('links') }}" class="admin-btn">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
