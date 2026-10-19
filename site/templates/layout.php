<?php
/** @var string $title_en */
/** @var string $title_zh */
/** @var string $description */
/** @var string $content */
/** @var string $active */
/** @var array $nav */
/** @var string $repo */
/** @var string $version */
?>
<!doctype html>
<html lang="en" data-lang="en" data-title-en="<?= e($title_en) ?>" data-title-zh="<?= e($title_zh) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <meta name="description" content="<?= e($description) ?>">
    <title><?= e($title_en) ?></title>
    <link rel="stylesheet" href="assets/site.css">
</head>
<body>
<header class="site-header">
    <div class="wrap header-inner">
        <a class="brand" href="index.html"><span class="brand-mark">mi</span>Gears</a>
        <nav class="site-nav" aria-label="Main">
            <?php foreach ($nav as $key => $label): ?>
                <a href="<?= e($key) ?>.html"<?= $active === $key ? ' class="active" aria-current="page"' : '' ?>><?= bi($label) ?></a>
            <?php endforeach; ?>
        </nav>
        <button class="lang-toggle" type="button" aria-label="Switch language">中文</button>
    </div>
</header>

<main>
    <?= $content ?>
</main>

<footer class="site-footer">
    <div class="wrap footer-inner">
        <p>
            <span class="lang-en">miGears is pre-release. Each module README is the authoritative source.</span>
            <span class="lang-zh">miGears 处于 pre-release。各模块 README 是权威来源。</span>
        </p>
        <p class="footer-links">
            <a href="<?= e($repo) ?>">GitHub</a>
            <span aria-hidden="true">·</span>
            <a href="<?= e($repo) ?>/blob/main/AGENTS.md">AGENTS.md</a>
            <span aria-hidden="true">·</span>
            <a href="<?= e($repo) ?>/blob/main/llms.txt">llms.txt</a>
            <span aria-hidden="true">·</span>
            <a href="<?= e($repo) ?>/blob/main/modules.yaml">modules.yaml</a>
        </p>
    </div>
</footer>

<script src="assets/site.js"></script>
</body>
</html>
