<?php
/** @var array $modules */
/** @var array $modulesByLayer */
/** @var array $layers */
?>
<section class="page-head">
    <div class="wrap">
        <p class="eyebrow"><span class="lang-en">Reference</span><span class="lang-zh">速查</span></p>
        <h1><span class="lang-en">The module map</span><span class="lang-zh">模块地图</span></h1>
        <p class="lede">
            <span class="lang-en">Every package, one layer at a time. Read “use when” before installing; read “avoid when” before keeping it.</span>
            <span class="lang-zh">每个包按层次排列。安装前读“适用”，保留前读“不适用”。</span>
        </p>
    </div>
</section>

<section class="section section-tight">
    <div class="wrap">
        <div class="filter-bar">
            <div class="filter-buttons" role="group" aria-label="Filter by layer">
                <button class="filter active" type="button" data-layer="all">
                    <span class="lang-en">All</span><span class="lang-zh">全部</span>
                </button>
                <?php foreach ($layers as $layer): ?>
                    <button class="filter" type="button" data-layer="<?= e($layer['id']) ?>"><?= bi($layer['label']) ?></button>
                <?php endforeach; ?>
            </div>
            <label class="search">
                <span class="visually-hidden"><span class="lang-en">Filter modules</span><span class="lang-zh">过滤模块</span></span>
                <input id="module-search" type="search" placeholder="Filter modules… / 过滤模块…" autocomplete="off">
            </label>
        </div>
        <p class="filter-status" id="filter-status" aria-live="polite"></p>
    </div>
</section>

<?php foreach ($layers as $layer): ?>
    <section class="section section-tight layer-section" data-layer-section="<?= e($layer['id']) ?>">
        <div class="wrap">
            <div class="layer-head">
                <h2><?= bi($layer['label']) ?></h2>
                <p><?= bi($layer['purpose']) ?></p>
            </div>
            <div class="module-list">
                <?php foreach ($modulesByLayer[$layer['id']] as $module): ?>
                    <?php
                    $search = strtolower(implode(' ', [
                        $module['package'],
                        $module['summary']['en'],
                        $module['summary']['zh'],
                        $layer['label']['en'],
                        $layer['label']['zh'],
                    ]));
                    ?>
                    <article class="module-card" id="<?= e($module['id']) ?>"
                             data-layer="<?= e($layer['id']) ?>"
                             data-search="<?= e($search) ?>">
                        <div class="module-head">
                            <h3><code><?= e($module['package']) ?></code></h3>
                            <?= publishedBadge($module) ?>
                        </div>
                        <p class="module-summary"><?= bi($module['summary']) ?></p>
                        <div class="module-grid">
                            <div>
                                <h4><span class="lang-en">Use when</span><span class="lang-zh">适用</span></h4>
                                <?= biList($module['use_when']) ?>
                            </div>
                            <div>
                                <h4><span class="lang-en">Avoid when</span><span class="lang-zh">不适用</span></h4>
                                <?= biList($module['avoid_when']) ?>
                            </div>
                        </div>
                        <?php if (($module['requires'] ?? []) !== []): ?>
                            <p class="requires">
                                <span class="lang-en">Pulls in:</span><span class="lang-zh">会带入：</span>
                                <?php foreach ($module['requires'] as $index => $requiredId): ?><?= $index > 0 ? ', ' : '' ?><a href="#<?= e($requiredId) ?>"><?= e($modules[$requiredId]['package']) ?></a><?php endforeach; ?>
                            </p>
                        <?php endif; ?>
                        <?php if (isset($module['note'])): ?>
                            <p class="module-note"><?= bi($module['note']) ?></p>
                        <?php endif; ?>
                        <a class="readme-link" href="<?= e($module['readme']) ?>">
                            <span class="lang-en">README</span><span class="lang-zh">模块文档</span> →
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endforeach; ?>
