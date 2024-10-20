// Barra de navegação 
var meta_click = document.querySelectorAll('[meta-click]');
meta_click.forEach(element => {
    element.addEventListener('click', () => {
        location.href = element.getAttribute('meta-click');
    });
});

// Alerta remover
if (document.getElementById('close')) {
    var closePOP = document.getElementById('close');
    var popup = document.getElementById('popup');
    closePOP.addEventListener('click', () => {
        popup.parentNode.removeChild(popup);
    });
}