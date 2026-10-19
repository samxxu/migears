<?php
/** @var array $modules */
/** @var array $recipes */
/** @var string $repo */
?>
<section class="page-head">
    <div class="wrap">
        <p class="eyebrow"><span class="lang-en">Ready-made stacks</span><span class="lang-zh">预制组合</span></p>
        <h1><span class="lang-en">Start from a stack</span><span class="lang-zh">从一套组合开始</span></h1>
        <p class="lede">
            <span class="lang-en">Each stack is a composer command, a module list, and a list of things you can remove. Nothing here is mandatory forever.</span>
            <span class="lang-zh">每套组合包含一条 composer 命令、一份模块清单，以及一份可以去掉的清单。没有什么是必须长期保留的。</span>
        </p>
    </div>
</section>

<section class="section section-tight">
    <div class="wrap">
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th><span class="lang-en">Stack</span><span class="lang-zh">组合</span></th>
                    <th><span class="lang-en">Best for</span><span class="lang-zh">适用</span></th>
                    <th><span class="lang-en">Direct packages</span><span class="lang-zh">直接依赖</span></th>
                    <th><span class="lang-en">Starter</span><span class="lang-zh">骨架</span></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($recipes as $recipe): ?>
                    <tr>
                        <td><a href="#<?= e($recipe['id']) ?>"><?= bi($recipe['name']) ?></a></td>
                        <td><?= bi($recipe['tagline']) ?></td>
                        <td><?= count($recipe['modules']) ?></td>
                        <td><?= $recipe['starter'] !== null ? '<span class="lang-en">Yes</span><span class="lang-zh">有</span>' : '<span class="lang-en">—</span><span class="lang-zh">—</span>' ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php foreach ($recipes as $recipe): ?>
    <section class="section stack-section" id="<?= e($recipe['id']) ?>">
        <div class="wrap">
            <div class="stack-head">
                <div>
                    <h2><?= bi($recipe['name']) ?></h2>
                    <p class="stack-tagline"><?= bi($recipe['tagline']) ?></p>
                </div>
                <div class="stack-badges">
                    <span class="badge badge-neutral"><?= count($recipe['modules']) ?> <span class="lang-en">direct</span><span class="lang-zh">直接依赖</span></span>
                    <?php if ($recipe['starter'] !== null): ?>
                        <a class="badge badge-ok" href="<?= e($repo) ?>/tree/main/<?= e($recipe['starter']) ?>">
                            <span class="lang-en">Runnable starter</span><span class="lang-zh">可运行骨架</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="code-block">
                <button class="copy" type="button" data-copy>Copy</button>
                <pre><code><?= e($recipe['composer']) ?></code></pre>
            </div>

            <div class="pill-groups">
                <div>
                    <p class="pill-label"><span class="lang-en">Install directly</span><span class="lang-zh">直接安装</span></p>
                    <div class="pill-row">
                        <?php foreach ($recipe['modules'] as $moduleId): ?>
                            <?= modulePill($modules[$moduleId], 'direct') ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php if (($recipe['pulled_in'] ?? []) !== []): ?>
                    <div>
                        <p class="pill-label"><span class="lang-en">Pulled in automatically</span><span class="lang-zh">自动带入</span></p>
                        <div class="pill-row">
                            <?php foreach ($recipe['pulled_in'] as $moduleId): ?>
                                <?= modulePill($modules[$moduleId], 'pulled') ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (($recipe['optional_modules'] ?? []) !== []): ?>
                    <div>
                        <p class="pill-label"><span class="lang-en">Add only when needed</span><span class="lang-zh">按需添加</span></p>
                        <div class="pill-row">
                            <?php foreach ($recipe['optional_modules'] as $moduleId): ?>
                                <?= modulePill($modules[$moduleId], 'optional') ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (isset($recipe['page_frontends'])): ?>
                <div class="stack-choice">
                    <p class="pill-label"><?= bi($recipe['page_frontends']['label']) ?></p>
                    <div class="pill-row">
                        <?php foreach ($recipe['page_frontends']['options'] as $optionId): ?>
                            <?= modulePill($modules[$optionId], 'choice') ?>
                        <?php endforeach; ?>
                    </div>
                    <p class="map-note"><?= bi($recipe['page_frontends']['note']) ?></p>
                </div>
            <?php endif; ?>

            <div class="stack-grid">
                <div class="card stack-list">
                    <h3><span class="lang-en">Use when</span><span class="lang-zh">适用</span></h3>
                    <?= biList($recipe['use_when']) ?>
                </div>
                <div class="card stack-list">
                    <h3><span class="lang-en">Avoid when</span><span class="lang-zh">不适用</span></h3>
                    <?= biList($recipe['avoid_when']) ?>
                </div>
                <div class="card stack-list">
                    <h3><span class="lang-en">Remove if</span><span class="lang-zh">可以去掉</span></h3>
                    <?= biList($recipe['remove_if']) ?>
                </div>
            </div>

            <?php if (isset($recipe['notes'])): ?>
                <div class="stack-notes">
                    <h3><span class="lang-en">Notes</span><span class="lang-zh">备注</span></h3>
                    <?= biList($recipe['notes']) ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endforeach; ?>
