<?php
/** @var array $modules */
/** @var array $recipes */
/** @var array $recipesDoc */
?>
<section class="page-head">
    <div class="wrap">
        <p class="eyebrow"><span class="lang-en">Selection guide</span><span class="lang-zh">选型指南</span></p>
        <h1><span class="lang-en">Three forks, four stacks</span><span class="lang-zh">三个岔路，四套组合</span></h1>
        <p class="lede">
            <span class="lang-en">Do not start from a module list. Start from what the project serves and who maintains it.</span>
            <span class="lang-zh">不要从模块清单开始，从项目输出什么、由谁维护开始。</span>
        </p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="fork-grid">
            <article class="card fork-card">
                <p class="fork-index">01</p>
                <h3><span class="lang-en">Does the project serve HTML?</span><span class="lang-zh">项目输出 HTML 吗？</span></h3>
                <p>
                    <span class="lang-en">JSON only means no view layer at all. Server-rendered pages mean you need a template engine.</span>
                    <span class="lang-zh">只输出 JSON 就不需要视图层；服务端渲染页面则需要模板引擎。</span>
                </p>
                <div class="fork-answers">
                    <a href="stacks.html#rest-api"><span class="lang-en">No, JSON only → REST API</span><span class="lang-zh">否，只输出 JSON → REST API</span></a>
                    <a href="#fork-pages"><span class="lang-en">Yes → next question</span><span class="lang-zh">是 → 下一个问题</span></a>
                </div>
            </article>

            <article class="card fork-card" id="fork-pages">
                <p class="fork-index">02</p>
                <h3><span class="lang-en">Are pages structurally repetitive?</span><span class="lang-zh">页面结构重复吗？</span></h3>
                <p>
                    <span class="lang-en">Page count is a weak signal. Repetition and who edits the page matter more.</span>
                    <span class="lang-zh">页数不是好判据。结构重复度与页面由谁编辑更重要。</span>
                </p>
                <div class="fork-answers">
                    <a href="stacks.html#minimal-site"><span class="lang-en">No → Minimal site</span><span class="lang-zh">否 → 小站</span></a>
                    <a href="stacks.html#content-site"><span class="lang-en">Yes → Content site</span><span class="lang-zh">是 → 内容站</span></a>
                </div>
            </article>

            <article class="card fork-card">
                <p class="fork-index">03</p>
                <h3><span class="lang-en">Do native clients need login?</span><span class="lang-zh">原生客户端需要登录吗？</span></h3>
                <p>
                    <span class="lang-en">Native apps need bearer tokens and refresh rotation, not browser sessions.</span>
                    <span class="lang-zh">原生应用需要 Bearer token 与刷新轮换，而不是浏览器会话。</span>
                </p>
                <div class="fork-answers">
                    <a href="stacks.html#rest-api"><span class="lang-en">No → REST API</span><span class="lang-zh">否 → REST API</span></a>
                    <a href="stacks.html#mobile-api"><span class="lang-en">Yes → Mobile API</span><span class="lang-zh">是 → 移动端 API</span></a>
                </div>
            </article>
        </div>
        <p class="map-note">
            <span class="lang-en">Declarative pages are optional. If you want them, pick pages (array DSL), yaml-pages, or xml-pages by preference. Plain template is always valid, including for a small site.</span>
            <span class="lang-zh">声明式页面不是必需。想用时，按喜好选择 pages（数组 DSL）、yaml-pages 或 xml-pages；纯 template 始终有效，页面少时也一样。</span>
        </p>
    </div>
</section>

<section class="section section-alt">
    <div class="wrap">
        <div class="section-head">
            <h2><span class="lang-en">The matrix</span><span class="lang-zh">对照表</span></h2>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th><span class="lang-en">Question</span><span class="lang-zh">问题</span></th>
                    <th><span class="lang-en">No</span><span class="lang-zh">否</span></th>
                    <th><span class="lang-en">Yes</span><span class="lang-zh">是</span></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><span class="lang-en">Does the project serve HTML?</span><span class="lang-zh">项目输出 HTML 吗？</span></td>
                    <td><a href="stacks.html#rest-api">REST API</a></td>
                    <td><span class="lang-en">Ask about pages</span><span class="lang-zh">继续问页面</span></td>
                </tr>
                <tr>
                    <td><span class="lang-en">Are pages repetitive or edited by non-PHP users?</span><span class="lang-zh">页面重复，或由非 PHP 人员编辑吗？</span></td>
                    <td><a href="stacks.html#minimal-site">Minimal site</a></td>
                    <td><a href="stacks.html#content-site">Content site</a></td>
                </tr>
                <tr>
                    <td><span class="lang-en">Do native clients need login?</span><span class="lang-zh">原生客户端需要登录吗？</span></td>
                    <td><a href="stacks.html#rest-api">REST API</a></td>
                    <td><a href="stacks.html#mobile-api">Mobile API</a></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="section-head">
            <h2><span class="lang-en">Hard rules</span><span class="lang-zh">硬规则</span></h2>
            <p>
                <span class="lang-en">These are not preferences. They are the mistakes the metadata is meant to prevent.</span>
                <span class="lang-zh">这些不是偏好，而是元数据要防住的错误。</span>
            </p>
        </div>
        <ul class="rule-list">
            <?php foreach ($recipesDoc['hard_rules'] as $rule): ?>
                <li><?= bi(['en' => $rule['en'], 'zh' => $rule['zh']]) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<section class="section section-alt">
    <div class="wrap">
        <div class="section-head">
            <h2><span class="lang-en">Add-ons</span><span class="lang-zh">按需叠加</span></h2>
            <p>
                <span class="lang-en">Add one only when the first column is true. If the second column is true, do not add it.</span>
                <span class="lang-zh">只有第一列成立时才添加；第二列成立时不要添加。</span>
            </p>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th><span class="lang-en">Module</span><span class="lang-zh">模块</span></th>
                    <th><span class="lang-en">Add when</span><span class="lang-zh">添加条件</span></th>
                    <th><span class="lang-en">Do not add when</span><span class="lang-zh">不要添加</span></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach (['i18n', 'mail', 'jobs', 'image', 'captcha', 'html-digest', 'ai-proxy', 'data-structure', 'mitable', 'rpc', 'security'] as $id): ?>
                    <?php $module = $modules[$id]; ?>
                    <tr>
                        <td><a href="modules.html#<?= e($id) ?>"><code><?= e($module['package']) ?></code></a></td>
                        <td><?= bi(['en' => $module['use_when']['en'][0], 'zh' => $module['use_when']['zh'][0]]) ?></td>
                        <td><?= bi(['en' => $module['avoid_when']['en'][0], 'zh' => $module['avoid_when']['zh'][0]]) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
