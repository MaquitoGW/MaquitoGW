@extends('layouts.admin')
@section('title', 'Mensagens')

@section('content')
    @php
        $unread = \App\Models\ContactMessage::where('status', 'pending')->count();
        $responded = \App\Models\ContactMessage::where('status', 'responded')->count();
    @endphp

    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Contato</span>
                <h1 class="admin-title">Mensagens de Contato</h1>
                <p class="admin-subtitle">Acompanhe as solicitações enviadas pelo formulário do site.</p>
            </div>
        </div>

        <div class="admin-stats">
            <div class="admin-stat">
                <span>Total de mensagens</span>
                <strong>{{ $messages->total() }}</strong>
            </div>
            <div class="admin-stat">
                <span>Não lidas</span>
                <strong>{{ $unread }}</strong>
            </div>
            <div class="admin-stat">
                <span>Respondidas</span>
                <strong>{{ $responded }}</strong>
            </div>
        </div>

        @if ($messages->count() > 0)
            <div class="admin-list">
                @foreach ($messages as $message)
                    <article class="admin-list-item">
                        <div>
                            <div class="admin-meta">
                                @if ($message->status === 'pending')
                                    <span class="status-badge warning">Pendente</span>
                                @elseif ($message->status === 'read')
                                    <span class="status-badge">Lida</span>
                                @else
                                    <span class="status-badge success">Respondida</span>
                                @endif
                                <span>{{ $message->created_at->format('d/m/Y H:i') }}</span>
                                <span>{{ $message->email }}</span>
                            </div>

                            <h2 class="admin-list-title">{{ $message->subject }}</h2>
                            <p class="admin-list-description">{{ $message->name }}</p>
                            <p>{{ \Illuminate\Support\Str::limit($message->message, 160) }}</p>
                        </div>

                        <div class="admin-actions">
                            <a href="{{ route('contacts.show', $message) }}" class="admin-btn">
                                <i class="fa-solid fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('contacts.delete', $message) }}"
                                onclick="return confirm('Tem certeza que deseja deletar?')" class="admin-btn admin-btn-danger">
                                <i class="fa-solid fa-trash"></i> Deletar
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="admin-card">
                {{ $messages->links() }}
            </div>
        @else
            <div class="empty-state">
                <p>Nenhuma mensagem de contato recebida ainda.</p>
            </div>
        @endif
    </div>
@endsection
