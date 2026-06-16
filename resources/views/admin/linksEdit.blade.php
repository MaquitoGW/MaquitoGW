@extends('layouts.admin')
@section('title', 'Editar Link')

@section('content')
    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Meus Links</span>
                <h1 class="admin-title">Editar link</h1>
                <p class="admin-subtitle">Atualize o destino, ícone e descrição do link.</p>
            </div>
            <a href="{{ route('links') }}" class="admin-btn">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

        <form action="{{ route('links.update', $link) }}" method="POST" class="admin-card">
            @csrf

            <label for="title">Título do link</label>
            <input type="text" id="title" name="title" value="{{ old('title', $link->title) }}" placeholder="Meu portfolio" required>
            @error('title') <p class="alert">{{ $message }}</p> @enderror

            <label for="url">URL do link</label>
            <input type="url" id="url" name="url" value="{{ old('url', $link->url) }}" placeholder="https://exemplo.com" required>
            @error('url') <p class="alert">{{ $message }}</p> @enderror

            <label for="icon">Ícone</label>
            <input type="text" id="icon" name="icon" value="{{ old('icon', $link->icon) }}" placeholder="github, instagram, website...">
            <p class="admin-list-description">Use nomes comuns como github, instagram, linkedin, youtube, whatsapp, email ou website.</p>
            @error('icon') <p class="alert">{{ $message }}</p> @enderror

            <label for="description">Descrição</label>
            <textarea id="description" name="description" rows="3" placeholder="Descrição curta do link">{{ old('description', $link->description) }}</textarea>
            @error('description') <p class="alert">{{ $message }}</p> @enderror

            <div class="admin-actions">
                <button type="submit" class="admin-btn-primary">Atualizar link</button>
                <a href="{{ route('links') }}" class="admin-btn">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
