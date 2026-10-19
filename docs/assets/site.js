(function () {
    'use strict';

    var root = document.documentElement;
    var langButton = document.querySelector('.lang-toggle');
    var search = document.getElementById('module-search');
    var status = document.getElementById('filter-status');
    var cards = Array.prototype.slice.call(document.querySelectorAll('.module-card'));
    var sections = Array.prototype.slice.call(document.querySelectorAll('[data-layer-section]'));
    var filters = Array.prototype.slice.call(document.querySelectorAll('.filter[data-layer]'));
    var activeLayer = 'all';

    function currentLang() {
        return root.dataset.lang === 'zh' ? 'zh' : 'en';
    }

    function applyFilter() {
        var query = search ? search.value.trim().toLowerCase() : '';
        var visible = 0;

        cards.forEach(function (card) {
            var layerOk = activeLayer === 'all' || card.dataset.layer === activeLayer;
            var searchOk = query === '' || card.dataset.search.indexOf(query) !== -1;
            var show = layerOk && searchOk;
            card.hidden = !show;
            if (show) {
                visible += 1;
            }
        });

        sections.forEach(function (section) {
            section.hidden = !section.querySelector('.module-card:not([hidden])');
        });

        if (status && cards.length > 0) {
            status.textContent = currentLang() === 'zh'
                ? '显示 ' + visible + ' 个模块'
                : visible + (visible === 1 ? ' module shown' : ' modules shown');
        }
    }

    function setLang(lang) {
        root.dataset.lang = lang;
        root.lang = lang === 'zh' ? 'zh-CN' : 'en';

        if (langButton) {
            langButton.textContent = lang === 'zh' ? 'EN' : '中文';
        }

        if (root.dataset.titleZh && root.dataset.titleEn) {
            document.title = lang === 'zh' ? root.dataset.titleZh : root.dataset.titleEn;
        }

        document.querySelectorAll('[data-copy]').forEach(function (button) {
            if (!button.dataset.busy) {
                button.textContent = lang === 'zh' ? '复制' : 'Copy';
            }
        });

        try {
            localStorage.setItem('migears-lang', lang);
        } catch (error) {
            // Ignore storage failures (private mode, blocked storage).
        }

        applyFilter();
    }

    var stored = null;
    try {
        stored = localStorage.getItem('migears-lang');
    } catch (error) {
        stored = null;
    }

    var initial = stored === 'en' || stored === 'zh'
        ? stored
        : (navigator.language || '').toLowerCase().indexOf('zh') === 0 ? 'zh' : 'en';

    setLang(initial);

    if (langButton) {
        langButton.addEventListener('click', function () {
            setLang(currentLang() === 'zh' ? 'en' : 'zh');
        });
    }

    filters.forEach(function (button) {
        button.addEventListener('click', function () {
            activeLayer = button.dataset.layer;
            filters.forEach(function (other) {
                other.classList.toggle('active', other === button);
            });
            applyFilter();
        });
    });

    if (search) {
        search.addEventListener('input', applyFilter);
    }

    document.querySelectorAll('[data-copy]').forEach(function (button) {
        button.addEventListener('click', function () {
            var code = button.parentElement.querySelector('code');
            if (!code || !navigator.clipboard) {
                return;
            }

            navigator.clipboard.writeText(code.textContent).then(function () {
                var lang = currentLang();
                button.dataset.busy = '1';
                button.textContent = lang === 'zh' ? '已复制' : 'Copied';
                window.setTimeout(function () {
                    delete button.dataset.busy;
                    button.textContent = lang === 'zh' ? '复制' : 'Copy';
                }, 1400);
            });
        });
    });
}());
