@extends('layouts.admin')
@section('title', 'Personalização')
@section('content')
    <style>
        .admin-page > form:nth-of-type(3),
        .admin-page > form:nth-of-type(4),
        label:has(input[name="admin_color_background"]),
        label:has(input[name="admin_color_sidebar"]),
        label:has(input[name="admin_color_card"]),
        label:has(input[name="admin_color_active"]),
        label:has(input[name="admin_color_surface_muted"]),
        label:has(input[name="admin_color_input"]),
        label:has(input[name="admin_color_surface_hover"]) {
            display: none !important;
        }
    </style>
    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Aparencia</span>
                <h1 class="admin-title">Personalizacao</h1>
                <p class="admin-subtitle">Defina as cores do site e do painel administrativo.</p>
            </div>
        </div>


        <form action="{{ route('customization.update') }}" method="post" class="admin-card">
            @csrf
            <h3>Tema do painel</h3>
            <label for="theme_mode">Modo visual</label>
            <select name="theme_mode" id="theme_mode">
                <option value="system" @if ($search('theme_mode', $search('admin_theme', 'system')) == 'system') selected @endif>Sistema</option>
                <option value="light" @if ($search('theme_mode', $search('admin_theme', 'system')) == 'light') selected @endif>Claro</option>
                <option value="dark" @if ($search('theme_mode', $search('admin_theme', 'system')) == 'dark') selected @endif>Escuro</option>
            </select>

            <div class="color-settings-grid">
                <label>Cor primaria <input type="color" name="color_primary"
                        value="{{ $search('color_primary', '#2f81f7') }}"></label>
                <label>Cor secundaria <input type="color" name="color_secondary"
                        value="{{ $search('color_secondary', '#1f6feb') }}"></label>
                <label>Cor primaria clara <input type="color" name="theme_color_primary_light"
                        value="{{ $search('theme_color_primary_light', $search('admin_color_primary_light', '#58a6ff')) }}"></label>
                <label>Fundo <input type="color" name="admin_color_background"
                        value="{{ $search('admin_color_background', '#0d1117') }}"></label>
                <label>Menu lateral <input type="color" name="admin_color_sidebar"
                        value="{{ $search('admin_color_sidebar', '#161b22') }}"></label>
                <label>Cards <input type="color" name="admin_color_card"
                        value="{{ $search('admin_color_card', '#161b22') }}"></label>
                <label>Item ativo <input type="color" name="admin_color_active"
                        value="{{ $search('admin_color_active', '#21262d') }}"></label>
                <label>Superficie <input type="color" name="admin_color_surface_muted"
                        value="{{ $search('admin_color_surface_muted', '#010409') }}"></label>
                <label>Texto <input type="color" name="theme_color_text"
                        value="{{ $search('theme_color_text', $search('admin_color_text', '#c9d1d9')) }}"></label>
                <label>Titulos <input type="color" name="theme_color_heading"
                        value="{{ $search('theme_color_heading', $search('admin_color_heading', '#f0f6fc')) }}"></label>
                <label>Texto secundario <input type="color" name="theme_color_muted"
                        value="{{ $search('theme_color_muted', $search('admin_color_text_muted', '#8b949e')) }}"></label>
                <label>Texto dos botoes <input type="color" name="theme_color_button_text"
                        value="{{ $search('theme_color_button_text', '#f8fafc') }}"></label>
                <label>Bordas <input type="color" name="theme_color_border"
                        value="{{ $search('theme_color_border', $search('admin_color_border', '#30363d')) }}"></label>
                <label>Inputs <input type="color" name="admin_color_input"
                        value="{{ $search('admin_color_input', '#0d1117') }}"></label>
                <label>Hover <input type="color" name="admin_color_surface_hover"
                        value="{{ $search('admin_color_surface_hover', '#1c2128') }}"></label>
            </div>

            <button type="submit">Atualizar tema do painel</button>
        </form>
        <form action="{{ route('customization.update') }}" method="post" class="admin-card">
            @csrf
            <input type="hidden" name="reset_theme_colors" value="1">
            <h3>Cores padrao</h3>
            <p class="admin-subtitle">Remove as cores personalizadas e volta para os padroes claro e escuro do sistema.</p>
            <button type="submit">Voltar para cores padroes</button>
        </form>
        <form action="{{ route('customization.update') }}" method="post">
            @csrf
            <h3>Personalizações adicionais</h3>
            <label for="color_primary">Cor principal</label>
            <input type="color" name="color_primary" value="{{ $search('color_primary', '#6200ff') }}">

            <label for="color_secondary">Cor secundaria</label>
            <input type="color" name="color_secondary" value="{{ $search('color_secondary', '#8400ff') }}">
            <button type="submit">Atualizar</button>
        </form>

        <form action="{{ route('customization.update') }}" method="post">
            @csrf
            <h3>Selecione um Thema</h3>
            <label for="theme_selection">Thema</label>
            <select name="theme_selection" id="theme_selection">
                <option value="auto" @if ($search('theme_selection', 'light') == 'auto') selected @endif>Auto (sistema)</option>
                <option value="light" @if ($search('theme_selection', 'light') == 'light') selected @endif>Claro</option>
                <option value="dark" @if ($search('theme_selection', 'light') == 'dark') selected @endif>Escuro</option>
            </select>
            <button type="submit">Atualizar</button>
        </form>

        <form action="{{ route('customization.update') }}" method="post">
            @csrf
            <h3>Selecione uma Skin</h3>
            <label for="site_skin">Skin</label>
            <select name="site_skin" id="site_skin">
                <option value="default" @if ($search('site_skin', 'default') == 'default') selected @endif>Default</option>
                <option value="minimalist" @if ($search('site_skin', 'default') == 'minimalist') selected @endif>Minimalista</option>
            </select>

            <div class="skin-preview">
                {{-- Criar como se fosse uma janela --}}
                <div class="skin-preview-window">
                    <div class="skin-preview-bar">
                        <span class="skin-preview-dot red"></span>
                        <span class="skin-preview-dot yellow"></span>
                        <span class="skin-preview-dot green"></span>
                    </div>
                </div>
                <img id="skin-preview-img" src="/img/skins/preview_{{ $search('site_skin', 'default') }}.png"
                    alt="Preview da skin selecionada">
            </div>
            <button type="submit">Atualizar</button>
        </form>

        <form action="{{ route('customization.update.images') }}" method="post" enctype="multipart/form-data">
            @csrf
            <h3>Imagens do usuário</h3>
            <label for="myphoto">Sua foto</label>
            <img class="images" src="{{ $search('myphoto', '/img/my.jpg') }}" alt="myphoto">
            <span class="info">Selecione uma imagem 1800x2500</span>
            <input type="file" name="myphoto" accept="image/*">

            <label for="bklogo">Imagem inicial</label>
            <img class="images" src="{{ $search('bklogo', '/img/bk_logo.png') }}" alt="Favicon">
            <span class="info">Selecione uma imagem 1000x1000</span>
            <input type="file" name="bklogo" accept="image/*">

            <button type="submit">Atualizar</button>
        </form>

        <form action="{{ route('customization.update.images') }}" method="post" enctype="multipart/form-data">
            @csrf
            <h3>Logos</h3>
            <label for="favicon">Favicon</label>
            <img class="images" src="{{ $search('favicon', '/img/favicon.png') }}" alt="Favicon">
            <span class="info">Selecione uma imagem 500x500</span>
            <input type="file" name="favicon" accept="image/*">

            {{-- <label for="logotipo">Logotipo Branca</label>
        <img class="images" src="{{ $search('logotipo', '/img/logo.png') }}" alt="Favicon">
        <span class="info">Selecione uma imagem 2560x500</span>
        <input type="file" name="logotipo" accept="image/*">

        <label for="logotipo_color">Logotipo Colorida</label>
        <img class="images none" src="{{ $search('logotipo_color', '/img/logo_color.png') }}" alt="Favicon">
        <span class="info">Selecione uma imagem 2560x500</span>
        <input type="file" name="logotipo_color" accept="image/*"> --}}

            <button type="submit">Atualizar</button>
        </form>

        <form class="wrap" id="logoForm" action="{{ route('customization.update') }}?encode=true" method="post">
            @csrf
            <h3>Logo Customizada</h3>

            <div class="card editor-col">
                <div class="row" style="justify-content:space-between; align-items:center; margin-bottom: 10px;">
                    <div>
                        <strong style="font-size:16px">Editor </strong>
                        <div class="small">Selecione texto -> clique na opção. Ícones Lucide podem ser inseridos no ponto
                            do
                            cursor.</div>
                    </div>
                </div>

                <div class="toolbar">
                    <select id="fontSelect" title="Fonte">
                        <option value="inherit">(herdar) — manter fonte da navbar</option>
                        <option value="Poppins, system-ui, Arial">Poppins</option>
                        <option value="Montserrat, system-ui, Arial">Montserrat</option>
                        <option value="Roboto, system-ui, Arial">Roboto</option>
                        <option value="Raleway, system-ui, Arial">Raleway</option>
                        <option value="Lobster, cursive">Lobster</option>
                        <option value="'Playfair Display', serif">Playfair Display</option>
                        <option value="Cinzel, serif">Cinzel</option>
                        <option value="'Times New Roman', serif">Times New Roman</option>
                    </select>

                    <input id="fontSize" type="number" min="8" max="160" value="32"
                        title="Tamanho (px)" style="width:90px" />

                    <input id="colorPicker" type="color" value="#ffffff" title="Cor" />

                    <button id="boldBtn" title="Negrito (aplica à seleção)">B</button>
                    <button id="italicBtn" title="Itálico (aplica à seleção)">I</button>

                    <button id="decreaseColorBtn" title="Diminuir cor/escurecer a cor selecionada">Escurecer cor</button>

                    <button id="selectAllBtn">Selecionar tudo</button>
                    <button id="clearFormattingBtn">Limpar formatação</button>
                    <button id="resetEditorBtn">Resetar texto</button>
                </div>

                <div class="editor" id="editor" contenteditable="true" spellcheck="false"
                    aria-label="Editor de logo">
                    {!! $search('custom_logo_code', 'MinhaLogo') !!}
                </div>

                <div style="display:flex; gap:12px; align-items:center; justify-content:space-between">
                    <div style="flex:1">
                        <div class="small">Biblioteca de ícones Lucide</div>
                        <div class="icon-search" style="margin-top:8px">
                            <input id="iconSearch" type="text"
                                placeholder="Buscar ícones: ex. heart, star, code..." />
                        </div>

                        <div class="icon-library">
                            <div class="icon-grid" id="iconList"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card preview-col">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px">
                    <strong>Preview & Código</strong>
                </div>
                <div class="preview-stage" id="previewStage" aria-live="polite"></div>
                <div class="code-output" id="codeOutput" readonly></div>
                <input type="hidden" name="custom_logo_code" id="codeOutputInput" />
            </div>

            <button id="submitLogo" type="submit">Atualizar</button>
        </form>
    </div>

    <script src="/js/logocreate.js"></script>
@endsection
