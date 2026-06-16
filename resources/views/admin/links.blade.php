@extends('layouts.admin')
@section('title', 'Meus Links')

@section('content')
    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Links públicos</span>
                <h1 class="admin-title">Minha página de links</h1>
                <p class="admin-subtitle">Gerencie o perfil público, URL e links compartilhados.</p>
            </div>

            <a href="{{ route('links.new') }}" class="admin-btn admin-btn-primary">
                <i class="fa-solid fa-plus"></i> Adicionar link
            </a>
        </div>

        <form action="{{ route('links.slug') }}" method="POST" class="admin-card">
            @csrf
            <h3>URL pública</h3>
            <label for="links_slug">Slug da página</label>
            <div class="admin-inline-field">
                <span>{{ rtrim(config('app.url'), '/') }}/</span>
                <input type="text" id="links_slug" name="links_slug" value="{{ old('links_slug', $user->links_slug) }}"
                    placeholder="links" pattern="[a-zA-Z0-9_-]+"
                    title="Use apenas letras, números, hífens e underscore">
                <button type="submit">Atualizar URL</button>
            </div>
            @error('links_slug')
                <p class="alert">{{ $message }}</p>
            @enderror
            <p class="admin-list-description">
                Sua página estará disponível em:
                <a href="{{ $publicLinkUrl }}" target="_blank" rel="noopener noreferrer">
                    <strong>{{ $publicLinkUrl }}</strong>
                </a>
            </p>
        </form>

        <form action="{{ route('links.profile') }}" method="POST" enctype="multipart/form-data" class="admin-card">
            @csrf
            <h3>Perfil da página</h3>

            <label for="links_display_name">Nome exibido</label>
            <input type="text" id="links_display_name" name="links_display_name"
                value="{{ old('links_display_name', $user->links_display_name) }}" placeholder="{{ $user->name }}">

            <label for="links_bio">Mini descrição</label>
            <textarea id="links_bio" name="links_bio" rows="3"
                placeholder="Uma frase curta sobre você ou seus links">{{ old('links_bio', $user->links_bio) }}</textarea>

            <div class="admin-profile-media">
                <div>
                    <label for="links_avatar">Foto de perfil</label>
                    @if ($user->links_avatar)
                        <img class="admin-media-preview avatar" src="{{ $user->links_avatar }}" alt="Foto de perfil">
                    @endif
                    <input type="file" id="links_avatar" name="links_avatar" accept="image/*">
                </div>

                <div>
                    <label for="links_banner">Banner opcional</label>
                    @if ($user->links_banner)
                        <img class="admin-media-preview banner" src="{{ $user->links_banner }}" alt="Banner da página">
                    @endif
                    <input type="file" id="links_banner" name="links_banner" accept="image/*">
                </div>
            </div>

            <button type="submit">Salvar perfil</button>
        </form>

        @if ($links->count() > 0)
            <div class="admin-list">
                @foreach ($links as $link)
                    <article class="admin-list-item">
                        <div>
                            <div class="admin-meta">
                                @if ($link->active)
                                    <span class="status-badge success">Ativo</span>
                                @else
                                    <span class="status-badge muted">Inativo</span>
                                @endif
                                @if ($link->icon)
                                    <span><i class="{{ str_starts_with($link->icon, 'fa-') ? $link->icon : 'fa-brands fa-' . $link->icon }}"></i> {{ $link->icon }}</span>
                                @endif
                            </div>

                            <h2 class="admin-list-title">{{ $link->title }}</h2>
                            <p class="admin-list-description">
                                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">
                                    {{ \Illuminate\Support\Str::limit($link->url, 90) }}
                                </a>
                            </p>
                            @if ($link->description)
                                <p>{{ $link->description }}</p>
                            @endif
                        </div>

                        <div class="admin-actions">
                            <form action="{{ route('links.toggle', $link) }}" method="POST" class="inline-form">
                                @csrf
                                <button type="submit">{{ $link->active ? 'Desativar' : 'Ativar' }}</button>
                            </form>
                            <a href="{{ route('links.edit', $link) }}" class="admin-btn">
                                <i class="fa-solid fa-pen"></i> Editar
                            </a>
                            <a href="{{ route('links.delete', $link) }}"
                                onclick="return confirm('Tem certeza que deseja deletar este link?')" class="admin-btn admin-btn-danger">
                                <i class="fa-solid fa-trash"></i> Deletar
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p>Você ainda não tem nenhum link adicionado.</p>
                <a href="{{ route('links.new') }}" class="admin-btn admin-btn-primary">
                    Adicionar o primeiro link
                </a>
            </div>
        @endif
    </div>
@endsection
