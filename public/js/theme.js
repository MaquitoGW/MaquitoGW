(function () {
    const cookieName = 'app-theme';
    const storageName = 'app-theme';
    const validThemes = ['light', 'dark', 'system'];
    const systemThemeQuery = window.matchMedia('(prefers-color-scheme: dark)');

    function getCookie(name) {
        return document.cookie
            .split('; ')
            .find(row => row.startsWith(name + '='))
            ?.split('=')[1];
    }

    function setCookie(name, value) {
        document.cookie = `${name}=${value}; path=/; max-age=31536000; SameSite=Lax`;
    }

    function getStoredTheme() {
        try {
            return localStorage.getItem(storageName);
        } catch (error) {
            return null;
        }
    }

    function setStoredTheme(value) {
        try {
            localStorage.setItem(storageName, value);
        } catch (error) {
            // localStorage can be unavailable in restricted browser modes.
        }
    }

    function configuredMode() {
        const mode = window.appThemeConfig?.mode || 'system';
        return mode === 'auto' ? 'system' : mode;
    }

    function selectedTheme() {
        const storedTheme = getStoredTheme();
        if (validThemes.includes(storedTheme)) {
            return storedTheme;
        }

        const cookieTheme = getCookie(cookieName);
        if (validThemes.includes(cookieTheme)) {
            return cookieTheme;
        }

        const mode = configuredMode();
        return validThemes.includes(mode) ? mode : 'system';
    }

    function effectiveTheme(theme) {
        return theme === 'system' ? (systemThemeQuery.matches ? 'dark' : 'light') : theme;
    }

    function updateIcons(isDark) {
        document.querySelectorAll('.theme-toggle i').forEach(icon => {
            if (!icon.classList.contains('fa-moon') && !icon.classList.contains('fa-sun')) {
                return;
            }

            icon.classList.toggle('fa-sun', !isDark);
            icon.classList.toggle('fa-moon', isDark);
        });
    }

    function syncButtons(theme) {
        document.querySelectorAll('[data-theme-option], [data-admin-theme-option]').forEach(button => {
            const value = button.dataset.themeOption || button.dataset.adminThemeOption;
            button.classList.toggle('active', value === theme);
        });
    }

    function applyTheme(theme, persist = false) {
        const selected = validThemes.includes(theme) ? theme : 'system';
        const effective = effectiveTheme(selected);

        if (persist) {
            setStoredTheme(selected);
            setCookie(cookieName, selected);
        }

        document.documentElement.classList.add('app-theme-configured');
        document.documentElement.dataset.appTheme = selected;
        document.documentElement.dataset.appThemeEffective = effective;
        document.documentElement.classList.toggle('dark', effective === 'dark');

        if (document.body) {
            document.body.classList.add('app-theme-configured');
            document.body.dataset.appTheme = selected;
            document.body.dataset.appThemeEffective = effective;
            document.body.dataset.adminTheme = selected;
            document.body.dataset.adminThemeEffective = effective;
        }

        updateIcons(effective === 'dark');
        syncButtons(selected);
    }

    window.appTheme = {
        apply: (theme) => applyTheme(theme, true),
        current: selectedTheme,
    };

    applyTheme(selectedTheme(), false);

    document.addEventListener('DOMContentLoaded', () => {
        applyTheme(selectedTheme(), false);

        document.querySelectorAll('.theme-toggle').forEach(toggle => {
            if (toggle.dataset.themeBound === 'true') return;
            toggle.dataset.themeBound = 'true';
            toggle.addEventListener('click', () => {
                const nextTheme = effectiveTheme(selectedTheme()) === 'dark' ? 'light' : 'dark';
                applyTheme(nextTheme, true);
            });
        });

        document.querySelectorAll('[data-theme-option], [data-admin-theme-option]').forEach(button => {
            if (button.dataset.themeBound === 'true') return;
            button.dataset.themeBound = 'true';
            button.addEventListener('click', () => {
                applyTheme(button.dataset.themeOption || button.dataset.adminThemeOption, true);
            });
        });

        const themeModeSelect = document.getElementById('theme_mode');
        if (themeModeSelect && themeModeSelect.dataset.themeBound !== 'true') {
            themeModeSelect.dataset.themeBound = 'true';
            themeModeSelect.addEventListener('change', () => {
                applyTheme(themeModeSelect.value, true);
            });

            themeModeSelect.form?.addEventListener('submit', () => {
                setStoredTheme(themeModeSelect.value);
                setCookie(cookieName, themeModeSelect.value);
            });
        }
    });

    systemThemeQuery.addEventListener('change', () => {
        if (selectedTheme() === 'system') {
            applyTheme('system', false);
        }
    });
})();
