window.onscroll = function () {
    var header = document.getElementById('header');

    if (window.pageYOffset > 0) { // Quando a página for rolada para baixo
        header.classList.add("sticky");
    } else { // Quando a página estiver no topo
        header.classList.remove("sticky");
    }

    scrollFunction(); // chamar função
    checkSections();
};

// Pega o botão
let topButton = document.getElementById("top-nav");

function scrollFunction() {
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        topButton.classList.add('visible') // Mostra o botão
    } else {
        topButton.classList.remove('visible') // Esconde o botão
    }
}

// Função para voltar ao topo quando o botão é clicado
topButton.onclick = function () {
    document.body.scrollTop = 0; // Para Safari
    document.documentElement.scrollTop = 0; // Para Chrome, Firefox, IE e Opera
};


var a = 0;
var text = "Desenvolvedor Web Full Stack.";
var write = document.getElementById('write');
setInterval(() => {
    var array = text.split("");

    if (a < array.length) {
        write.textContent += array[a];
        a++;
    }
}, 50);

// Função para fechar e abrir navbar

var openNav = document.getElementById('open');
var closeNav = document.getElementById('close');
var navBar = document.getElementById('navBar');
var listNav = document.getElementById('listNav');

openNav.addEventListener('click', () => {
    openNav.classList.remove('visible');
    openNav.classList.add('not-visible');

    closeNav.classList.remove('not-visible');
    closeNav.classList.add('visible');

    navBar.classList.remove('not-visible');
    navBar.classList.add('visible');

    header.classList.remove("sticky");
    document.body.style.overflow = "hidden";
    listNav.classList.add('activeNav');

    document.querySelector('.activeNav').addEventListener('click', close);
});

closeNav.addEventListener('click', close);

function close()  {
    closeNav.classList.remove('visible');
    closeNav.classList.add('not-visible');

    openNav.classList.remove('not-visible');
    openNav.classList.add('visible');

    navBar.classList.add('not-visible');
    navBar.classList.remove('visible');

    header.classList.add("sticky");
    document.body.style.overflow = "auto";
    listNav.classList.remove('activeNav');
}

// Outros

// Verifica se pelo menos parte do elemento está visível na tela
function isInViewport(el) {
    const rect = el.getBoundingClientRect();
    const windowHeight = (window.innerHeight || document.documentElement.clientHeight);
    const windowWidth = (window.innerWidth || document.documentElement.clientWidth);

    return (
        (rect.top >= 0 && rect.top <= windowHeight) || // Parte superior visível
        (rect.bottom >= 0 && rect.bottom <= windowHeight) // Parte inferior visível
    );
}

// Remove a classe "active" de todos os links
function removeActiveClasses() {
    document.querySelectorAll('ul.list li a').forEach(link => {
        link.classList.remove('active');
    });
}

// Função que verifica se uma seção está visível e ativa o link correspondente
function checkSections() {
    const sections = document.querySelectorAll('section');
    let currentActive = null; // Guardar o link ativo atualmente
    const links = {
        "sobre-mim": document.getElementById('lk-sobre'),
        "portfolio": document.getElementById('lk-portfolio'),
        "habilidades": document.getElementById('lk-habilidades'),
        "contato": document.getElementById('lk-contato')
    };

    sections.forEach(section => {
        if (isInViewport(section)) {
            currentActive = links[section.id];
        }
    });

    // Se houver uma seção ativa, atualiza a navegação
    if (currentActive) {
        removeActiveClasses();
        currentActive.classList.add('active');
    }
}