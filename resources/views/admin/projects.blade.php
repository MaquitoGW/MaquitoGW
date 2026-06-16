@extends('layouts.admin')
@section('title', 'Projetos')
@section('content')

    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Portfólio</span>
                <h1 class="admin-title">Meus projetos</h1>
                <p class="admin-subtitle">Gerencie projetos, tecnologias, demos e arquivos publicados.</p>
            </div>

            <button action-click="{{ route('projects.new') }}" class="new-project-btn">
                <i class="fa-solid fa-plus"></i> Novo projeto
            </button>
        </div>

    <div class="projects-grid">
        @forelse ($projects as $project)
            <div class="project-card-v3">
                <div class="project-thumbnail">
                    <img src="{{ $project['thumbnail'] }}" alt="Thumbnail do projeto {{ $project['name'] }}">
                </div>
                <div class="project-content-v3">
                    <h3 class="project-title-v3">{{ $project['name'] }}</h3>
                    <p class="project-description-v3">
                        {{ $project['preview'] }}
                    </p>
                    <div class="project-tags-v3">
                        @foreach ($project['skills'] as $tag)
                            <span class="project-tag-v3">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <div class="project-actions-v3">
                        <button action-click="projects/delete/{{ $project['demo'] }}" class="action-btn-v3 delete-btn-v3"><i
                                class="fas fa-trash-alt"></i> Apagar</button>
                        <button action-click="projects/edit/{{ $project['demo'] }}" class="action-btn-v3 edit-btn-v3"><i
                                class="fas fa-edit"></i> Editar</button>
                        <button action-click="{{ route('projects.files', $project['demo']) }}" class="action-btn-v3"><i
                                class="fas fa-folder-open"></i> Arquivos</button>
                        <button action-click="/demo/{{ $project['demo'] }}" class="action-btn-v3 demo-btn-v3"><i
                                class="fas fa-external-link-alt"></i> Ver
                            Demo</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fa-solid fa-folder-open"></i>
                <h3>Nenhum projeto cadastrado</h3>
                <p>Adicione seu primeiro projeto para ele aparecer no portfólio.</p>
                <button action-click="{{ route('projects.new') }}" class="new-project-btn">
                    <i class="fa-solid fa-plus"></i> Novo projeto
                </button>
            </div>
        @endforelse

    </div>
    </div>
@endsection
