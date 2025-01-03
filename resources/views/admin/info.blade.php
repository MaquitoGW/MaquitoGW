@extends('layouts.admin')
@section('title', 'Informações')
@section('content')

    @if ($infoLanguages >= 0 && $infoLanguages < 2)
        @if ($infoLanguages == 0)
            <div class="options-list">
                @if (env('MULTIPLE_LANGUAGES'))
                    <a href="{{ route('info.add.en_US') }}"> Informações em en-US</a>
                    <a href="{{ route('info.add.pt_BR') }}"> Informações em pt-BR</a>
                @else
                    <a href="{{ route('info.add.' . env('APP_FAKER_LOCALE')) }}">
                        Informações em {{ env('APP_FAKER_LOCALE') }}
                    </a>
                @endif

                <p class="alert">
                    <span class="icon"><i class="fa-light fa-circle-exclamation"></i></span>
                    <span>Por favor adicione suas informações.</span>
                </p>
            </div>
        @endif

        @foreach ($infos as $item)
            @if ($item->language != 'en_US' && env('MULTIPLE_LANGUAGES'))
                <div class="options-list">
                    <a href="{{ route('info.add.en_US') }}"> Informações em en-US</a>

                    <p class="alert">
                        <span class="icon"><i class="fa-light fa-circle-exclamation"></i></span>
                        <span>Por favor adicione suas informações em en-US para que o seu portifólio funcione.</span>
                    </p>
                </div>
            @endif
            @if ($item->language != 'pt_BR' && env('MULTIPLE_LANGUAGES'))
                <div class="options-list">
                    <a href="{{ route('info.add.pt_BR') }}"> Informações em pt-BR</a>

                    <p class="alert">
                        <span class="icon"><i class="fa-light fa-circle-exclamation"></i></span>
                        <span>Por favor adicione suas informações em pt-BR para que o seu portifólio funcione.</span>
                    </p>
                </div>
            @endif
        @endforeach
    @endif

    @if (isset($route[1]) == 'add')
        <form action="{{ route('info.add') }}" method="post">
            @csrf
            <h3>Minhas Informações ({{ $route[2] }})</h3>

            <label for="name">Nome</label>
            <input required type="text" name="name" placeholder="Insira apenas seu primeiro nome">

            <label for="fullname">Nome completo</label>
            <input required type="text" name="fullname" placeholder="Insira apenas seu nome completo">

            <label for="position">Qual seu cargo</label>
            <input required type="text" name="position" value="{{ $info->position }}" placeholder="Insira em que cargo você atua atualmente">

            <label for="description">Descrição</label>
            <textarea name="description" cols="30" rows="10" placeholder="Fale um pouco sobre você..."></textarea>

            <button name="language" value="{{ $route[2] }}" type="submit">Adicionar</button>
        </form>
    @endif

    @foreach ($infos as $info)
        <form action="{{ route('info.update') }}" method="post">
            @csrf
            <h3>Minhas Informações ({{ $info->language }})</h3>

            <label for="name">Nome</label>
            <input required type="text" name="name" value="{{ $info->name }}"
                placeholder="Insira apenas seu primeiro nome">

            <label for="fullname">Nome completo</label>
            <input required type="text" name="fullname" value="{{ $info->fullname }}"
                placeholder="Insira apenas seu nome completo">

            <label for="position">Qual seu cargo</label>
            <input required type="text" name="position" value="{{ $info->position }}" placeholder="Insira em que cargo você atua atualmente">

            <label for="description">Descrição</label>
            <textarea name="description" cols="30" rows="10" placeholder="Fale um pouco sobre você...">{{ $info->description }}</textarea>

            <button name="id" value="{{ $info->id }}" type="submit">Atualizar</button>
        </form>
    @endforeach


    <form
        action="@if ($contactsCheck > 0) {{ route('contacts.update') }} @else {{ route('contacts.add') }} @endif"
        method="post" enctype="multipart/form-data">
        @csrf
        <h3>Meu currículo</h3>
        @if (isset($contacts->csv))
            <p class="info">
                <span class="icon">
                    <i class="fa-solid fa-link"></i>
                    <a href="{{ $contacts->csv }}">Veja seu currículo</a>
                </span>
            </p>
        @endif
        <input type="file" name="csv" accept=".pdf">

        <h3>Minhas redes sociais</h3>

        <label for="instagram">Instagram</label>
        <span class="icon">
            <i class="fa-brands fa-instagram"></i>
            <input type="text" name="instagram"
                @if ($contactsCheck > 0) value="{{ $contacts->instagram }}" @endif placeholder="Digite seu @">
        </span>

        <label for="Twitter">Twitter (X)</label>
        <span class="icon">
            <i class="fa-brands fa-twitter"></i>
            <input type="text" name="twitter" @if ($contactsCheck > 0) value="{{ $contacts->twitter }}" @endif
                placeholder="Digite seu @">
        </span>

        <label for="linkedin">Linkedin</label>
        <span class="icon">
            <i class="fa-brands fa-linkedin"></i>
            <input type="text" name="linkedin"
                @if ($contactsCheck > 0) value="{{ $contacts->linkedin }}" @endif placeholder="Digite seu @">
        </span>

        <label for="github">GitHub</label>
        <span class="icon">
            <i class="fa-brands fa-github"></i>
            <input type="text" name="github" @if ($contactsCheck > 0) value="{{ $contacts->github }}" @endif
                placeholder="Digite seu @">
        </span>

        <h3>Meus Contatos</h3>

        @if (!$contacts)
            <p class="alert">
                <span class="icon"><i class="fa-light fa-circle-exclamation"></i></span>
                <span>Por favor adicione pelo menos uma informações de contato.</span>
            </p>
        @endif

        <label for="tel">Telefone</label>
        <input type="tel" name="tel" @if ($contactsCheck > 0) value="{{ $contacts->tel }}" @endif
            placeholder="Digite dessa forma: +5531993292980 ">

        <label for="email_personal">E-mail pessoal</label>
        <input type="email" name="email_personal"
            @if ($contactsCheck > 0) value="{{ $contacts->email_personal }}" @endif
            placeholder="endereco@email.com">

        <label for="email_business">E-mail empresarial</label>
        <input type="email" name="email_business"
            @if ($contactsCheck > 0) value="{{ $contacts->email_business }}" @endif
            placeholder="endereco@email.com">

        <button @if ($contactsCheck > 0) name="id" value="{{ $contacts->id }}" @endif type="submit">
            @if ($contactsCheck > 0)
                Atualizar
            @else
                Adicionar
            @endif
        </button>
    </form>
@endsection
