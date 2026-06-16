function rgbToHsl(r, g, b) {
    r /= 255; g /= 255; b /= 255;
    const max = Math.max(r, g, b), min = Math.min(r, g, b);
    let h = 0, s = 0, l = (max + min) / 2;
    if (max !== min) {
        const d = max - min;
        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
        switch (max) {
            case r: h = (g - b) / d + (g < b ? 6 : 0); break;
            case g: h = (b - r) / d + 2; break;
            case b: h = (r - g) / d + 4; break;
        }
        h /= 6;
    }
    return [h * 360, s, l];
}

function hslToRgb(h, s, l) {
    h /= 360;
    let r, g, b;
    if (s === 0) r = g = b = l;
    else {
        function hue2rgb(p, q, t) {
            if (t < 0) t += 1;
            if (t > 1) t -= 1;
            if (t < 1 / 6) return p + (q - p) * 6 * t;
            if (t < 1 / 2) return q;
            if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
            return p;
        }
        const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
        const p = 2 * l - q;
        r = hue2rgb(p, q, h + 1 / 3);
        g = hue2rgb(p, q, h);
        b = hue2rgb(p, q, h - 1 / 3);
    }
    return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
}

function hexToRgb(hex) {
    hex = hex.replace('#', '');
    if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
    const bigint = parseInt(hex, 16);
    return [(bigint >> 16) & 255, (bigint >> 8) & 255, bigint & 255];
}

function rgbToHex(r, g, b) {
    return '#' + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('');
}

function darkenHex(hex, factor = 0.15) {
    const [r, g, b] = hexToRgb(hex);
    let [h, s, l] = rgbToHsl(r, g, b);
    l = Math.max(0, l - factor);
    const [nr, ng, nb] = hslToRgb(h, s, l);
    return rgbToHex(nr, ng, nb);
}

function camelToKebab(str) {
    str = str.replace(/([a-z0-9])([A-Z])/g, '$1-$2');
    str = str.replace(/([A-Z]+)([A-Z][a-z])/g, '$1-$2');
    return str.toLowerCase();
}

// DOM refs
const editor = document.getElementById('editor');
const previewStage = document.getElementById('previewStage');
const fontSelect = document.getElementById('fontSelect');
const fontSize = document.getElementById('fontSize');
const colorPicker = document.getElementById('colorPicker');
const boldBtn = document.getElementById('boldBtn');
const italicBtn = document.getElementById('italicBtn');
const decreaseColorBtn = document.getElementById('decreaseColorBtn');
const iconSearch = document.getElementById('iconSearch');
const iconList = document.getElementById('iconList');
const codeOutput = document.getElementById('codeOutput');
const codeOutputInput = document.getElementById('codeOutputInput');
const previewUpdateDebounce = 80; // ms
const logoForm = document.getElementById('logoForm');
const submitLogoBtn = document.getElementById('submitLogo');

logoForm.addEventListener('submit', (e) => {
    e.preventDefault();
    submitLogoBtn.addEventListener('click', () => {
        codeOutputInput.value = codeOutput.textContent;
        logoForm.submit();
    });
});

// utility: wrap selection in a span with given style object
function wrapSelectionWithStyle(styleObj) {
    const sel = window.getSelection();
    if (!sel.rangeCount) return;
    const range = sel.getRangeAt(0);
    if (range.collapsed) {
        // If collapsed, insert a styled empty span and place caret inside
        const span = document.createElement('span');
        span.textContent = ' ';
        applyStyleObjToElement(span, styleObj);
        range.insertNode(span);
        // move caret inside span (after inserted text)
        sel.collapse(span, span.childNodes.length);
        updatePreviewSoon();
        return;
    }
    // Extract contents and wrap
    const content = range.extractContents();
    const span = document.createElement('span');
    span.appendChild(content);
    applyStyleObjToElement(span, styleObj);
    range.insertNode(span);
    // reselect the newly inserted span
    sel.removeAllRanges();
    const newRange = document.createRange();
    newRange.selectNodeContents(span);
    sel.addRange(newRange);
    updatePreviewSoon();
}

function applyStyleObjToElement(el, styleObj) {
    for (const k in styleObj) {
        el.style[k] = styleObj[k];
    }
}

// apply font, size to selection (or entire editor if none)
function applyToSelectionOrAll(styleObj) {
    const sel = window.getSelection();
    if (sel.rangeCount && !sel.getRangeAt(0).collapsed) {
        wrapSelectionWithStyle(styleObj);
    } else {
        // apply to whole editor content by wrapping everything in a span (preserve existing tags)
        // We'll create a wrapper and move children.
        const wrapper = document.createElement('span');
        applyStyleObjToElement(wrapper, styleObj);
        while (editor.firstChild) wrapper.appendChild(editor.firstChild);
        editor.appendChild(wrapper);
        updatePreviewSoon();
    }
}

// toolbar actions
boldBtn.addEventListener('click', () => {
    applyToSelectionOrAll({ fontWeight: '700' });
});
italicBtn.addEventListener('click', () => {
    applyToSelectionOrAll({ fontStyle: 'italic' });
});

fontSelect.addEventListener('change', () => {
    applyToSelectionOrAll({ fontFamily: fontSelect.value });
});

fontSize.addEventListener('change', () => {
    const v = parseInt(fontSize.value) || 14;
    applyToSelectionOrAll({ fontSize: v + 'px' });
});

colorPicker.addEventListener('change', () => {
    applyToSelectionOrAll({ color: colorPicker.value });
});

// decrease color (escurecer) — aplica na seleção: encontra cor atual (ou usa picker) e reduz luminosidade
decreaseColorBtn.addEventListener('click', () => {
    const sel = window.getSelection();
    let targetHex = colorPicker.value;
    if (sel.rangeCount && !sel.getRangeAt(0).collapsed) {
        // try to inspect first element color
        const range = sel.getRangeAt(0);
        const container = range.commonAncestorContainer;
        // try to find a parent span with color style
        let el = container.nodeType === 1 ? container : container.parentElement;
        while (el && el !== editor) {
            const color = window.getComputedStyle(el).color;
            if (color) {
                // rgb(...)
                const m = color.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)/);
                if (m) {
                    targetHex = rgbToHex(parseInt(m[1]), parseInt(m[2]), parseInt(m[3]));
                    break;
                }
            }
            el = el.parentElement;
        }
    }
    const darker = darkenHex(targetHex, 0.16);
    applyToSelectionOrAll({ color: darker });
});

// selection helpers
document.getElementById('selectAllBtn')?.addEventListener('click', () => {
    const range = document.createRange();
    range.selectNodeContents(editor);
    const sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(range);
});

document.getElementById('clearFormattingBtn')?.addEventListener('click', () => {
    // remove all style attributes and spans but preserve text and icons
    const html = editor.innerHTML;
    // create fragment and walk nodes
    const temp = document.createElement('div');
    temp.innerHTML = html;
    (function cleanse(node) {
        if (node.nodeType === 1) {
            // keep icons (<i data-lucide>) intact
            if (node.tagName.toLowerCase() === 'i' && node.hasAttribute('data-lucide')) return;
            // unwrap span but keep children
            if (node.tagName.toLowerCase() === 'span') {
                if (node.childNodes.length) {
                    const parent = node.parentNode;
                    while (node.firstChild) parent.insertBefore(node.firstChild, node);
                    parent.removeChild(node);
                    return;
                }
            }
            // remove style attributes
            node.removeAttribute('style');
            // recurse children
            Array.from(node.childNodes).forEach(child => cleanse(child));
        }
    })(temp);
    editor.innerHTML = temp.innerHTML || 'MinhaLogo';
    updatePreviewSoon();
});

document.getElementById('resetEditorBtn')?.addEventListener('click', () => {
    editor.innerHTML = 'Minha<span style="opacity:0.9">Logo</span>';
    updatePreviewSoon();
});

// ICONS: populate icon list based on lucide.icons keys
const lucideIcons = Object.keys(lucide.icons || {});
function searchIcons(term) {
    iconList.innerHTML = '';
    term = term.trim().toLowerCase();
    const matches = lucideIcons.filter(k => k.includes(term)).slice(0, 40);
    matches.forEach(key => {
        key = camelToKebab(key);

        const card = document.createElement('div');
        card.className = 'icon-card';
        card.title = key;

        const view = document.createElement('div');
        view.style.width = '36px'; view.style.height = '36px';
        view.style.display = 'inline-block';

        // create <i data-lucide="name"></i>
        const iel = document.createElement('i');
        iel.setAttribute('data-lucide', key);
        iel.style.display = 'inline-block';
        iel.style.verticalAlign = 'middle';
        iel.style.width = (parseInt(fontSize.value) || 32) + 'px';
        iel.style.height = (parseInt(fontSize.value) || 32) + 'px';
        view.appendChild(iel);

        const label = document.createElement('div');
        label.className = 'icon-name';
        label.textContent = key;
        card.appendChild(view);
        card.appendChild(label);
        card.addEventListener('click', () => insertIconAtCaret(key));
        iconList.appendChild(card);
    });

    lucide.createIcons();
}

iconSearch.addEventListener('input', (e) => searchIcons(e.target.value));

function insertIconAtCaret(name) {
    const iel = document.createElement('i');
    iel.setAttribute('data-lucide', name);
    // add a small inline-block style so it aligns with text: we won't add color here so CSS can style it
    iel.style.display = 'inline-block';
    iel.style.verticalAlign = 'middle';
    iel.style.width = (parseInt(fontSize.value) || 32) + 'px';
    iel.style.height = (parseInt(fontSize.value) || 32) + 'px';

    // insert into editor
    insertNodeAtCaret(iel);
    // place caret after svg
    placeCaretAfter(iel);
    // update preview and code

    updatePreviewSoon();
}

/* helpers to insert node at caret */
function insertNodeAtCaret(node) {
    editor.focus();
    const sel = window.getSelection();
    if (!sel.rangeCount) {
        const r = document.createRange();
        r.selectNodeContents(editor);
        r.collapse(false);
        sel.removeAllRanges();
        sel.addRange(r);
    }
    const range = sel.getRangeAt(0);
    range.deleteContents();
    range.insertNode(node);
}
function placeCaretAfter(node) {
    const r = document.createRange();
    r.setStartAfter(node);
    r.collapse(true);
    const sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(r);
}

// preview
let previewTimeout = null;
function updatePreviewSoon() {
    if (previewTimeout) clearTimeout(previewTimeout);
    previewTimeout = setTimeout(updatePreview, previewUpdateDebounce);
}

function updatePreview() {
    // clone editor content into preview stage; run lucide.createIcons()
    previewStage.innerHTML = '';
    // clone to avoid moving nodes
    const clone = editor.cloneNode(true);
    // strip contenteditable attributes and other irrelevant stuff
    clone.querySelectorAll('[contenteditable]').forEach(n => n.removeAttribute('contenteditable'));
    // ensure icons have sizes & inherit color
    clone.querySelectorAll('i[data-lucide]').forEach(i => {
        // if icon is inside a span with color, set svg to inherit color by not specifying color
        i.style.width = (parseInt(getComputedStyle(editor).fontSize) || 32) + 'px';
        i.style.height = (parseInt(getComputedStyle(editor).fontSize) || 32) + 'px';
    });
    previewStage.appendChild(clone);
    // render lucide svgs in the preview
    try { lucide.createIcons(); } catch (e) {/* ignore */ }
    // build code output
    buildGeneratedCode();
}

// build generated HTML + CSS
function buildGeneratedCode() {
    let inner = editor.innerHTML.trim();
    inner = inner.replace(/contenteditable="[^"]*"/g, '');

    let fullOutput = '';
    if (!inner.includes('div-logo')) {
        fullOutput = `<div class="div-logo" style="display: flex; align-items: center; justify-content: flex-start; gap: 5px;">${inner}</div>`;
    } else {
        fullOutput = inner;
    }

    codeOutput.textContent = fullOutput;
    codeOutputInput.value = fullOutput;
}

// copy helpers
function copyText(text) {
    navigator.clipboard?.writeText(text).then(() => {
        flashMessage('Copiado para a área de transferência');
    }).catch(() => {
        // fallback
        const ta = document.createElement('textarea');
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); flashMessage('Copiado'); } catch (e) { alert('Erro ao copiar'); }
        ta.remove();
    });
}
function flashMessage(msg) {
    // simples toast
    const t = document.createElement('div');
    t.textContent = msg;
    t.style.position = 'fixed';
    t.style.right = '18px';
    t.style.bottom = '18px';
    t.style.padding = '10px 14px';
    t.style.background = 'rgba(22,18,38,0.9)';
    t.style.border = '1px solid rgba(124,92,255,0.18)';
    t.style.color = '#fff';
    t.style.borderRadius = '8px';
    t.style.zIndex = 9999;
    document.body.appendChild(t);
    setTimeout(() => t.style.opacity = '0.0', 900);
    setTimeout(() => t.remove(), 1400);
}

// initial population of some icons (popular)
['star', 'heart', 'user', 'code', 'coffee', 'menu', 'settings', 'chevron-right', 'search', 'home', 'box'].forEach(k => {
    searchIcons(k);
});

// when typing in editor, update preview
editor.addEventListener('input', () => updatePreviewSoon());
editor.addEventListener('keyup', () => updatePreviewSoon());
editor.addEventListener('mouseup', () => updatePreviewSoon());

// initial render
updatePreview();

// Search-on-enter convenience
document.getElementById('iconSearch').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') { e.preventDefault(); searchIcons(e.target.value); }
});