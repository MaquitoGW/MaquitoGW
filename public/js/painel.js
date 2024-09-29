// Barra de navegação 
var meta_click = document.querySelectorAll('[meta-click]');
meta_click.forEach(element => {
    element.addEventListener('click', () => {
        location.href = element.getAttribute('meta-click');
    });
});