<?php
/** @var array $modules */
/** @var array $modulesByLayer */
/** @var array $layers */
/** @var array $recipes */
/** @var string $repo */
/** @var string $version */
?>
<section class="hero">
    <div class="wrap">
        <p class="eyebrow">
            <span class="lang-en"><?= count($modules) ?> modules · <?= count($layers) ?> layers · <?= count($recipes) ?> stacks</span>
            <span class="lang-zh"><?= count($modules) ?> 个模块 · <?= count($layers) ?> 层 · <?= count($recipes) ?> 套组合</span>
        </p>
        <h1>
            <span class="lang-en">Pick the pieces,<br>not the pile.</span>
            <span class="lang-zh">选对模块，<br>而不是全装。</span>
        </h1>
        <p class="lede">
            <span class="lang-en">miGears is 30 small PHP packages. This site tells you which few to install, how they fit together, and what to leave out.</span>
            <span class="lang-zh">miGears 是 30 个小型 PHP 包。本站告诉你该装哪几个、它们如何搭配，以及哪些可以不要。</span>
        </p>
        <div class="hero-actions">
            <a class="button button-primary" href="stacks.html">
                <span class="lang-en">Choose a stack</span><span class="lang-zh">选择组合</span>
            </a>
            <a class="button" href="modules.html">
                <span class="lang-en">Browse the map</span><span class="lang-zh">浏览模块地图</span>
            </a>
            <a class="button button-quiet" href="<?= e($repo) ?>/blob/main/AGENTS.md">
                <span class="lang-en">For AI agents</span><span class="lang-zh">给 AI agent</span>
            </a>
        </div>
        <p class="version-note">
            <span class="lang-en">Workspace version <?= e($version) ?> · pre-release · Packagist versions may lag behind.</span>
            <span class="lang-zh">工作区版本 <?= e($version) ?> · pre-release · Packagist 上的版本可能滞后。</span>
        </p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="section-head">
            <h2><span class="lang-en">60-second start</span><span class="lang-zh">60 秒起步</span></h2>
            <p>
                <span class="lang-en">The minimal starter is a real project: routing, a template, a logger, and a dev error page.</span>
                <span class="lang-zh">最小 starter 是一个真项目：路由、模板、日志与开发错误页。</span>
            </p>
        </div>
        <div class="code-block">
            <button class="copy" type="button" data-copy>Copy</button>
            <pre><code>git clone <?= e($repo) ?>.git
cd migears/starters/minimal-site
php bin/setup.php
php -S 127.0.0.1:8080 -t public public/index.php</code></pre>
        </div>
        <p class="version-note">
            <span class="lang-en">M1 starter runs against the current 2.3 tree. The public repositories may lag until the current test round is released.</span>
            <span class="lang-zh">M1 starter 运行在当前 2.3 代码树上。当前测试轮发布前，公开仓库可能滞后。</span>
        </p>
    </div>
</section>

<section class="section section-alt" id="map">
    <div class="wrap">
        <div class="section-head">
            <h2><span class="lang-en">The module map</span><span class="lang-zh">模块地图</span></h2>
            <p>
                <span class="lang-en">Six layers. The same layer is not a bundle: install a module only when it earns its place.</span>
                <span class="lang-zh">六个层次。同一层不是套餐：只有真正有用的模块才装。</span>
            </p>
        </div>
        <div class="layer-map">
            <?php foreach ($layers as $layer): ?>
                <div class="layer-row">
                    <div class="layer-label">
                        <strong><?= bi($layer['label']) ?></strong>
                        <span><?= bi($layer['purpose']) ?></span>
                    </div>
                    <div class="layer-mods">
                        <?php foreach ($modulesByLayer[$layer['id']] as $module): ?>
                            <a class="chip<?= in_array($module['id'], ['web', 'template'], true) ? ' chip-core' : '' ?>"
                               href="modules.html#<?= e($module['id']) ?>"><?= e($module['package']) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="map-note">
            <span class="lang-en">All modules share one release train. A few are still completing their test round; that is release timing, not a functional difference.</span>
            <span class="lang-zh">所有模块在同一条发布列车上。少数模块仍在完成测试轮；这是发布节奏，不是功能差异。</span>
        </p>
    </div>
</section>

<section class="section" id="stacks">
    <div class="wrap">
        <div class="section-head">
            <h2><span class="lang-en">Four stacks, four starting points</span><span class="lang-zh">四套组合，四个起点</span></h2>
            <p>
                <span class="lang-en">Start with one. Remove what you do not need. Add a module only when its “use when” matches.</span>
                <span class="lang-zh">先选一套，去掉不需要的，再只在“适用”命中时添加模块。</span>
            </p>
        </div>
        <div class="recipe-grid">
            <?php foreach ($recipes as $recipe): ?>
                <a class="card recipe-card" href="stacks.html#<?= e($recipe['id']) ?>">
                    <h3><?= bi($recipe['name']) ?></h3>
                    <p><?= bi($recipe['tagline']) ?></p>
                    <p class="recipe-count"><?= count($recipe['modules']) ?>
                        <span class="lang-en">direct packages</span><span class="lang-zh">个直接依赖</span>
                    </p>
                    <div class="pill-row">
                        <?php foreach (array_slice($recipe['modules'], 0, 4) as $moduleId): ?>
                            <span class="pill pill-direct"><?= e($modules[$moduleId]['package']) ?></span>
                        <?php endforeach; ?>
                        <?php if (count($recipe['modules']) > 4): ?>
                            <span class="pill pill-more">+<?= count($recipe['modules']) - 4 ?></span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-agent">
    <div class="wrap agent-inner">
        <div>
            <h2><span class="lang-en">Built to be read by agents</span><span class="lang-zh">也为 AI agent 而写</span></h2>
            <p>
                <span class="lang-en">The same facts the site uses are available as plain files. An agent can start from AGENTS.md, choose a recipe, and stop before installing anything that does not fit.</span>
                <span class="lang-zh">站点使用的同一份事实也有纯文本版本。agent 从 AGENTS.md 开始，选一套组合，并在安装不合适的东西之前停下。</span>
            </p>
        </div>
        <ul class="file-list">
            <li><a href="<?= e($repo) ?>/blob/main/AGENTS.md">AGENTS.md</a><span class="lang-en">Selection order and hard rules</span><span class="lang-zh">选型顺序与硬规则</span></li>
            <li><a href="<?= e($repo) ?>/blob/main/llms.txt">llms.txt</a><span class="lang-en">Compact index for LLMs</span><span class="lang-zh">面向 LLM 的紧凑索引</span></li>
            <li><a href="<?= e($repo) ?>/blob/main/modules.yaml">modules.yaml</a><span class="lang-en">All 30 modules as data</span><span class="lang-zh">30 个模块的结构化数据</span></li>
            <li><a href="<?= e($repo) ?>/blob/main/recipes.yaml">recipes.yaml</a><span class="lang-en">Four stacks and hard rules</span><span class="lang-zh">四套组合与硬规则</span></li>
        </ul>
    </div>
</section>
