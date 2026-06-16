document.querySelectorAll('[meta-click]').forEach(element => {
    element.addEventListener('click', () => {
        location.href = '/admin/' + element.getAttribute('meta-click');
    });
});

const closePopup = document.getElementById('close');
const popup = document.getElementById('popup');

if (closePopup && popup) {
    closePopup.addEventListener('click', () => {
        popup.parentNode.removeChild(popup);
    });
}

document.querySelectorAll('[action-click]').forEach(element => {
    element.addEventListener('click', () => {
        location.href = element.getAttribute('action-click');
    });
});

const sidebarToggle = document.querySelector('.sidebar-toggle');

if (localStorage.getItem('admin-sidebar') === 'collapsed') {
    document.body.classList.add('sidebar-collapsed');
}

if (sidebarToggle) {
    sidebarToggle.addEventListener('click', () => {
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem(
            'admin-sidebar',
            document.body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded'
        );
    });
}

const skinSelect = document.getElementById('site_skin');
const skinPreviewImg = document.getElementById('skin-preview-img');

if (skinSelect && skinPreviewImg) {
    skinSelect.addEventListener('change', function () {
        skinPreviewImg.src = `/img/skins/preview_${this.value}.png`;
    });
}
