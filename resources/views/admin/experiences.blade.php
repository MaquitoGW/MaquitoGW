@extends('layouts.admin')
@section('title', 'Informações Profissionais')

@section('content')
    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Carreira</span>
                <h1 class="admin-title">Informações Profissionais</h1>
                <p class="admin-subtitle">Gerencie as experiências exibidas no template minimalista.</p>
            </div>

            <a href="{{ route('experiences.new') }}" class="admin-btn admin-btn-primary">
                <i class="fa-solid fa-plus"></i> Adicionar experiência
            </a>
        </div>

        @if (session('success'))
            <div class="popup success" style="position: relative; inset: auto;">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($experiences->count() > 0)
            <div class="admin-list">
                @foreach ($experiences as $experience)
                    <article class="admin-list-item">
                        <div>
                            <div class="admin-meta">
                                @if ($experience->current)
                                    <span class="status-badge success">Atual</span>
                                @else
                                    <span class="status-badge muted">Anterior</span>
                                @endif
                                <span>
                                    {{ $experience->start_date->format('M Y') }}
                                    -
                                    {{ $experience->current || !$experience->end_date ? 'Presente' : $experience->end_date->format('M Y') }}
                                </span>
                                @if ($experience->location)
                                    <span><i class="fa-solid fa-location-dot"></i> {{ $experience->location }}</span>
                                @endif
                            </div>

                            <h2 class="admin-list-title">{{ $experience->position }}</h2>
                            <p class="admin-list-description">{{ $experience->company }}</p>

                            @if ($experience->description)
                                <p>{{ \Illuminate\Support\Str::limit($experience->description, 160) }}</p>
                            @endif
                        </div>

                        <div class="admin-actions">
                            <a href="{{ route('experiences.edit', $experience) }}" class="admin-btn">
                                <i class="fa-solid fa-pen"></i> Editar
                            </a>
                            <a href="{{ route('experiences.delete', $experience) }}"
                                onclick="return confirm('Tem certeza que deseja deletar?')" class="admin-btn admin-btn-danger">
                                <i class="fa-solid fa-trash"></i> Deletar
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p>Você ainda não tem experiências adicionadas.</p>
                <a href="{{ route('experiences.new') }}" class="admin-btn admin-btn-primary">Adicionar primeira experiência</a>
            </div>
        @endif
    </div>
@endsection
