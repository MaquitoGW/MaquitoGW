@extends('layouts.admin')
@section('title', 'Configurações')
@section('content')

<div class="options-set">
    <label for="server-name">Nome do Servidor</label>
    <input type="text" id="server-name" name="server-name" placeholder="Nome do servidor" />
    <!-- Usado como o nome do servidor -->

    <label for="gamemode">Modo de Jogo</label>
    <select id="gamemode" name="gamemode">
        <option value="survival">Sobrevivência</option>
        <option value="creative">Criativo</option>
        <option value="adventure">Aventura</option>
    </select>
    <!-- Define o modo de jogo para novos jogadores -->

    <label for="force-gamemode">Forçar Modo de Jogo</label>
    <select id="force-gamemode" name="force-gamemode">
        <option value="false">Desativado</option>
        <option value="true">Ativado</option>
    </select>
    <!-- Impede o servidor de enviar para o cliente valores de modo de jogo diferentes do valor salvo pelo servidor durante a criação do mundo -->

    <label for="difficulty">Dificuldade</label>
    <select id="difficulty" name="difficulty">
        <option value="peaceful">Pacífico</option>
        <option value="easy">Fácil</option>
        <option value="normal">Normal</option>
        <option value="hard">Difícil</option>
    </select>
    <!-- Define a dificuldade do mundo -->

    <label for="allow-cheats">Permitir Cheats</label>
    <select id="allow-cheats" name="allow-cheats">
        <option value="false">Desativado</option>
        <option value="true">Ativado</option>
    </select>
    <!-- Se verdadeiro, cheats como comandos podem ser usados -->

    <label for="max-players">Número Máximo de Jogadores</label>
    <input type="number" id="max-players" name="max-players" min="1" placeholder="Número máximo de jogadores" />
    <!-- O número máximo de jogadores que podem jogar no servidor -->

    <label for="online-mode">Modo Online</label>
    <select id="online-mode" name="online-mode">
        <option value="true">Ativado</option>
        <option value="false">Desativado</option>
    </select>
    <!-- Se verdadeiro, todos os jogadores conectados devem estar autenticados no Xbox Live -->

    <label for="allow-list">Lista de Permissões</label>
    <select id="allow-list" name="allow-list">
        <option value="false">Desativado</option>
        <option value="true">Ativado</option>
    </select>
    <!-- Se verdadeiro, todos os jogadores conectados devem estar listados no arquivo allowlist.json -->

    <label for="server-port">Porta do Servidor (IPv4)</label>
    <input type="number" id="server-port" name="server-port" min="1" max="65535" placeholder="Porta do servidor (IPv4)" />
    <!-- Qual porta IPv4 o servidor deve ouvir -->

    <label for="server-portv6">Porta do Servidor (IPv6)</label>
    <input type="number" id="server-portv6" name="server-portv6" min="1" max="65535" placeholder="Porta do servidor (IPv6)" />
    <!-- Qual porta IPv6 o servidor deve ouvir -->

    <label for="enable-lan-visibility">Visibilidade LAN</label>
    <select id="enable-lan-visibility" name="enable-lan-visibility">
        <option value="true">Ativado</option>
        <option value="false">Desativado</option>
    </select>
    <!-- O servidor deve escutar e responder aos clientes que procuram servidores na LAN -->

    <label for="view-distance">Distância de Visualização</label>
    <input type="number" id="view-distance" name="view-distance" min="5" placeholder="Distância máxima de visualização (em chunks)" />
    <!-- A distância máxima permitida de visualização em número de chunks -->

    <label for="tick-distance">Distância de Tick</label>
    <input type="number" id="tick-distance" name="tick-distance" min="4" max="12" placeholder="Distância de tick" />
    <!-- O mundo será "tickado" essa quantidade de chunks longe de qualquer jogador -->

    <label for="player-idle-timeout">Tempo Limite de Inatividade do Jogador</label>
    <input type="number" id="player-idle-timeout" name="player-idle-timeout" min="0" placeholder="Tempo limite de inatividade do jogador (em minutos)" />
    <!-- Após um jogador estar inativo por este número de minutos, ele será expulso -->

    <label for="max-threads">Número Máximo de Threads</label>
    <input type="number" id="max-threads" name="max-threads" min="0" placeholder="Número máximo de threads" />
    <!-- Número máximo de threads que o servidor tentará usar. 0 significa usar o máximo possível -->

    <label for="default-player-permission-level">Nível de Permissão Padrão do Jogador</label>
    <select id="default-player-permission-level" name="default-player-permission-level">
        <option value="visitor">Visitante</option>
        <option value="member">Membro</option>
        <option value="operator">Operador</option>
    </select>
    <!-- Nível de permissão para novos jogadores que estão se juntando pela primeira vez -->

    <label for="texturepack-required">Textura Obrigatória</label>
    <select id="texturepack-required" name="texturepack-required">
        <option value="false">Desativado</option>
        <option value="true">Ativado</option>
    </select>
    <!-- Força os clientes a usarem pacotes de texturas no mundo atual -->

    <label for="content-log-file-enabled">Registro de Erros de Conteúdo</label>
    <select id="content-log-file-enabled" name="content-log-file-enabled">
        <option value="false">Desativado</option>
        <option value="true">Ativado</option>
    </select>
    <!-- Habilita o registro de erros de conteúdo em um arquivo -->

    <label for="compression-threshold">Limiar de Compressão</label>
    <input type="number" id="compression-threshold" name="compression-threshold" min="0" max="65535" placeholder="Limiar de compressão" />
    <!-- Determina o menor tamanho da carga útil de rede bruta a ser comprimida -->

    <label for="compression-algorithm">Algoritmo de Compressão</label>
    <select id="compression-algorithm" name="compression-algorithm">
        <option value="zlib">Zlib</option>
        <option value="snappy">Snappy</option>
    </select>
    <!-- Determina o algoritmo de compressão a ser usado para a rede -->

    <label for="server-authoritative-movement">Movimento Autoritativo do Servidor</label>
    <select id="server-authoritative-movement" name="server-authoritative-movement">
        <option value="client-auth">Autenticação do Cliente</option>
        <option value="server-auth">Autenticação do Servidor</option>
        <option value="server-auth-with-rewind">Autenticação do Servidor com Retrocesso</option>
    </select>
    <!-- Habilita o movimento autoritativo do servidor -->

    <label for="player-movement-score-threshold">Limiar de Pontuação de Movimento do Jogador</label>
    <input type="number" id="player-movement-score-threshold" name="player-movement-score-threshold" placeholder="Limiar de pontuação de movimento do jogador" />
    <!-- O número de intervalos de tempo incongruentes necessários antes que um comportamento anormal seja relatado -->

    <label for="player-movement-action-direction-threshold">Limiar de Direção da Ação de Movimento do
        Jogador</label>
    <input type="number" id="player-movement-action-direction-threshold" name="player-movement-action-direction-threshold" min="0" max="1" step="0.01" placeholder="Limiar de direção da ação de movimento do jogador" />
    <!-- A quantidade que a direção de ataque do jogador e a direção de visualização podem diferir -->

    <label for="player-movement-distance-threshold">Limiar de Distância de Movimento do Jogador</label>
    <input type="number" id="player-movement-distance-threshold" name="player-movement-distance-threshold" step="0.01" placeholder="Limiar de distância de movimento do jogador" />
    <!-- A diferença entre as posições do servidor e do cliente que precisa ser excedida antes que um comportamento anormal seja detectado -->

    <label for="player-movement-duration-threshold-in-ms">Limiar de Duração do Movimento do Jogador (em
        milissegundos)</label>
    <input type="number" id="player-movement-duration-threshold-in-ms" name="player-movement-duration-threshold-in-ms" placeholder="Limiar de duração do movimento do jogador (em milissegundos)" />
    <!-- A duração do tempo em que as posições do servidor e do cliente podem estar fora de sincronia antes que a pontuação de movimento anormal seja incrementada -->

    <label for="correct-player-movement">Corrigir Movimento do Jogador</label>
    <select id="correct-player-movement" name="correct-player-movement">
        <option value="false">Desativado</option>
        <option value="true">Ativado</option>
    </select>
    <!-- Se verdadeiro, a posição do cliente será corrigida para a posição do servidor se a pontuação de movimento exceder o limiar -->

    <label for="server-authoritative-block-breaking">Quebrar Blocos Autoritativos do Servidor</label>
    <select id="server-authoritative-block-breaking" name="server-authoritative-block-breaking">
        <option value="false">Desativado</option>
        <option value="true">Ativado</option>
    </select>
    <!-- Se verdadeiro, o servidor calculará operações de mineração de blocos em sincronia com o cliente -->

    <label for="chat-restriction">Restrição de Chat</label>
    <select id="chat-restriction" name="chat-restriction">
        <option value="None">Nenhuma</option>
        <option value="Dropped">Mensagens Descartadas</option>
        <option value="Disabled">Desativado</option>
    </select>
    <!-- Representa o nível de restrição aplicado ao chat para cada jogador que entra no servidor -->

    <label for="disable-player-interaction">Desativar Interação do Jogador</label>
    <select id="disable-player-interaction" name="disable-player-interaction">
        <option value="false">Desativado</option>
        <option value="true">Ativado</option>
    </select>
    <!-- Se verdadeiro, o servidor informará aos clientes que devem ignorar outros jogadores ao interagir com o mundo -->

    <label for="client-side-chunk-generation-enabled">Geração de Chunk do Lado do Cliente Habilitada</label>
    <select id="client-side-chunk-generation-enabled" name="client-side-chunk-generation-enabled">
        <option value="true">Ativado</option>
        <option value="false">Desativado</option>
    </select>
    <!-- Se verdadeiro, o servidor informará aos clientes que eles têm a capacidade de gerar chunks visuais fora das distâncias de interação do jogador -->

    <label for="block-network-ids-are-hashes">IDs de Rede de Bloco São Hashes</label>
    <select id="block-network-ids-are-hashes" name="block-network-ids-are-hashes">
        <option value="true">Ativado</option>
        <option value="false">Desativado</option>
    </select>
    <!-- Se verdadeiro, o servidor enviará IDs de bloco hashados em vez de IDs que começam de 0 e aumentam. Esses IDs são estáveis e não mudarão, independentemente de outras mudanças de bloco -->

    <label for="disable-persona">Desativar Persona</label>
    <select id="disable-persona" name="disable-persona">
        <option value="false">Desativado</option>
        <option value="true">Ativado</option>
    </select>
    <!-- Uso interno apenas -->

    <label for="disable-custom-skins">Desativar Skins Personalizados</label>
    <select id="disable-custom-skins" name="disable-custom-skins">
        <option value="false">Desativado</option>
        <option value="true">Ativado</option>
    </select>
    <!-- Se verdadeiro, desabilita skins personalizadas de jogadores que foram personalizadas fora dos ativos da loja do Minecraft ou ativos do jogo -->

    <label for="server-build-radius-ratio">Razão de Raio de Construção do Servidor</label>
    <select id="server-build-radius-ratio" name="server-build-radius-ratio">
        <option value="Disabled">Desativado</option>
        <option value="0.0">0.0</option>
        <option value="0.1">0.1</option>
        <!-- Adicione outras opções conforme necessário -->
    </select>
    <!-- Se "Desativado", o servidor calculará dinamicamente quanto da visão do jogador ele irá gerar, atribuindo o restante ao cliente para construir. Caso contrário, a partir da razão substituída, o servidor informará quanto da visão do jogador deve gerar, ignorando a capacidade de hardware do cliente. Válido apenas se a geração de chunks do lado do cliente estiver ativada -->

    <label for="showcoordinates">Mostrar Coordenadas</label>
    <select id="showcoordinates" name="showcoordinates">
        <option value="true">Ativado</option>
        <option value="false">Desativado</option>
    </select>
    <!-- Se verdadeiro, mostra as coordenadas no jogo -->



</div>

@endsection
