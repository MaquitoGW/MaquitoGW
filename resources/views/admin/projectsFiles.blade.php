@extends('layouts.admin')
@section('title', 'Arquivos do projeto')

@section('content')
    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Projeto</span>
                <h1 class="admin-title">Arquivos de {{ $project->name }}</h1>
                <p class="admin-subtitle">Gerencie os arquivos publicados em <code>public/demo/{{ $project->demo }}</code>.</p>
            </div>
            <div class="admin-actions">
                <a href="{{ url('/demo/' . $project->demo) }}" target="_blank" rel="noopener noreferrer" class="admin-btn">
                    <i class="fas fa-external-link-alt"></i> Abrir demo
                </a>
                <a href="{{ url('admin/projects/edit/' . $project->demo) }}" class="admin-btn">
                    <i class="fas fa-edit"></i> Editar projeto
                </a>
                <a href="{{ route('projects') }}" class="admin-btn">
                    <i class="fa-solid fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="admin-alert">{{ session('success') }}</div>
        @endif

        <div class="file-manager-grid">
            <div class="admin-card file-manager-panel">
                <div class="file-manager-path">
                    <span class="admin-eyebrow">Pasta atual</span>
                    <strong>/{{ $currentPath ?: '' }}</strong>
                </div>

                <div class="file-manager-actions">
                    <form method="POST" action="{{ route('projects.files.upload', $project->demo) }}" enctype="multipart/form-data" class="inline-form file-upload-form">
                        @csrf
                        <input type="hidden" name="path" value="{{ $currentPath }}">
                        <input type="file" name="files[]" multiple>
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="fas fa-upload"></i> Enviar
                        </button>
                    </form>

                    <form method="POST" action="{{ route('projects.files.folder', $project->demo) }}" class="inline-form file-folder-form">
                        @csrf
                        <input type="hidden" name="path" value="{{ $currentPath }}">
                        <input type="text" name="folder" placeholder="Nova pasta">
                        <button type="submit" class="admin-btn">
                            <i class="fas fa-folder-plus"></i> Criar
                        </button>
                    </form>
                </div>

                <div class="file-list">
                    @if (!is_null($parentPath))
                        <a href="{{ route('projects.files', ['uuid' => $project->demo, 'path' => $parentPath]) }}" class="file-row">
                            <span class="file-icon"><i class="fas fa-level-up-alt"></i></span>
                            <span class="file-name">..</span>
                            <span class="file-meta">Voltar</span>
                        </a>
                    @endif

                    @foreach ($directories as $directory)
                        <div class="file-row">
                            <a href="{{ route('projects.files', ['uuid' => $project->demo, 'path' => $directory['relative']]) }}" class="file-row-main">
                                <span class="file-icon"><i class="fas fa-folder"></i></span>
                                <span class="file-name">{{ $directory['name'] }}</span>
                                <span class="file-meta">Pasta</span>
                            </a>
                            <form method="POST" action="{{ route('projects.files.delete', $project->demo) }}" class="inline-form" onsubmit="return confirm('Remover esta pasta e todo o conteudo?')">
                                @csrf
                                <input type="hidden" name="path" value="{{ $currentPath }}">
                                <input type="hidden" name="target" value="{{ $directory['relative'] }}">
                                <button type="submit" class="admin-btn admin-btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    @endforeach

                    @foreach ($files as $file)
                        <div class="file-row">
                            <div class="file-row-main">
                                <span class="file-icon"><i class="fas fa-file"></i></span>
                                <span class="file-name">{{ $file['name'] }}</span>
                                <span class="file-meta">{{ $file['size'] }} | {{ $file['modified'] }}</span>
                            </div>
                            <div class="file-row-actions">
                                @if ($file['editable'])
                                    <a href="{{ route('projects.files', ['uuid' => $project->demo, 'path' => $currentPath, 'file' => $file['relative']]) }}" class="admin-btn">
                                        <i class="fas fa-code"></i> Editar
                                    </a>
                                @endif
                                <a href="{{ url('/demo/' . $project->demo . '/' . $file['relative']) }}" target="_blank" rel="noopener noreferrer" class="admin-btn">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('projects.files.delete', $project->demo) }}" class="inline-form" onsubmit="return confirm('Remover este arquivo?')">
                                    @csrf
                                    <input type="hidden" name="path" value="{{ $currentPath }}">
                                    <input type="hidden" name="target" value="{{ $file['relative'] }}">
                                    <button type="submit" class="admin-btn admin-btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    @if (count($directories) === 0 && count($files) === 0)
                        <div class="empty-state">Nenhum arquivo nesta pasta.</div>
                    @endif
                </div>
            </div>

            <div class="admin-card file-editor-panel">
                @if ($selectedFile)
                    <div class="file-editor-header">
                        <div>
                            <span class="admin-eyebrow">Editor</span>
                            <h3>{{ $selectedFile['name'] }}</h3>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('projects.files.save', $project->demo) }}" class="file-editor-form">
                        @csrf
                        <input type="hidden" name="path" value="{{ $currentPath }}">
                        <input type="hidden" name="target" value="{{ $selectedFile['relative'] }}">
                        <textarea name="content" spellcheck="false">{{ $selectedFile['content'] }}</textarea>
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="fas fa-save"></i> Salvar arquivo
                        </button>
                    </form>
                @else
                    <div class="empty-state">Selecione um arquivo editavel para alterar o conteudo.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
