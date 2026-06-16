@extends('layouts.admin')
@section('title', 'Mensagem')

@section('content')
    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Mensagem</span>
                <h1 class="admin-title">{{ $message->subject }}</h1>
                <p class="admin-subtitle">Recebida em {{ $message->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <a href="{{ route('contacts') }}" class="admin-btn">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="admin-detail-grid">
            <article class="admin-card">
                <div class="admin-meta">
                    @if ($message->status === 'pending')
                        <span class="status-badge warning">Pendente</span>
                    @elseif ($message->status === 'read')
                        <span class="status-badge">Lida</span>
                    @else
                        <span class="status-badge success">Respondida</span>
                    @endif
                    <span>{{ $message->name }}</span>
                    <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                </div>

                <h2 class="admin-list-title">{{ $message->subject }}</h2>
                <div class="message-box">
                    {{ $message->message }}
                </div>
            </article>

            <aside class="admin-card">
                <h3>Ações</h3>
                <div class="admin-actions vertical">
                    @if ($message->status === 'pending')
                        <form action="{{ route('contacts.mark-read', $message) }}" method="POST" class="inline-form full">
                            @csrf
                            <button type="submit">Marcar como lida</button>
                        </form>
                    @endif

                    @if ($message->status !== 'responded')
                        <form action="{{ route('contacts.mark-responded', $message) }}" method="POST" class="inline-form full">
                            @csrf
                            <button type="submit" class="admin-btn-primary">Marcar como respondida</button>
                        </form>
                    @endif

                    <a href="{{ route('contacts.delete', $message) }}"
                        onclick="return confirm('Tem certeza que deseja deletar?')" class="admin-btn admin-btn-danger">
                        <i class="fa-solid fa-trash"></i> Deletar mensagem
                    </a>
                </div>
            </aside>
        </div>
    </div>
@endsection
