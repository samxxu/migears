<?php

declare(strict_types=1);

/**
 * Build the static site in docs/ from modules.yaml and recipes.yaml.
 *
 * No network access and no timestamps: the output is deterministic, so CI can
 * rebuild it and fail when docs/ is stale.
 */

$root = dirname(__DIR__);

$modulesDoc = yaml_parse_file($root . '/modules.yaml');
$recipesDoc = yaml_parse_file($root . '/recipes.yaml');

if (!is_array($modulesDoc) || !is_array($recipesDoc)) {
    fwrite(STDERR, "Could not parse modules.yaml or recipes.yaml\n");
    exit(1);
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function bi(array $value): string
{
    return '<span class="lang-en">' . e($value['en']) . '</span>'
        . '<span class="lang-zh">' . e($value['zh']) . '</span>';
}

function biList(array $value, string $class = ''): string
{
    $html = '';

    foreach (['en', 'zh'] as $lang) {
        $html .= '<ul class="bi-list lang-' . $lang . ($class !== '' ? ' ' . $class : '') . '">';

        foreach ($value[$lang] as $line) {
            $html .= '<li>' . e($line) . '</li>';
        }

        $html .= '</ul>';
    }

    return $html;
}

$modules = [];
$modulesByLayer = [];
$layers = [];

foreach ($modulesDoc['layers'] as $layer) {
    $layers[$layer['id']] = $layer;
    $modulesByLayer[$layer['id']] = [];
}

foreach ($modulesDoc['modules'] as $module) {
    $modules[$module['id']] = $module;
    $modulesByLayer[$module['layer']][] = $module;
}

$recipes = [];
foreach ($recipesDoc['recipes'] as $recipe) {
    $recipes[$recipe['id']] = $recipe;
}

$repo = rtrim((string) $modulesDoc['repository'], '/');
$version = (string) $modulesDoc['version'];

function modulePill(array $module, string $kind = 'direct'): string
{
    $published = $module['published'];

    return '<a class="pill pill-' . $kind . '" href="modules.html#' . e($module['id']) . '">'
        . '<span class="pill-name">' . e($module['package']) . '</span>'
        . ($published === null ? '<span class="pill-note"><span class="lang-en">pending</span><span class="lang-zh">待发布</span></span>' : '')
        . '</a>';
}

function publishedBadge(array $module): string
{
    if ($module['published'] === null) {
        return '<span class="badge badge-neutral">'
            . '<span class="lang-en">pending release</span><span class="lang-zh">待发布</span>'
            . '</span>';
    }

    return '<span class="badge badge-ok">Packagist ' . e($module['published']) . '</span>';
}

function renderTemplate(string $template, array $vars): string
{
    extract($vars, EXTR_SKIP);
    ob_start();
    require $template;

    return (string) ob_get_clean();
}

$docs = $root . '/docs';
$assets = $docs . '/assets';

if (!is_dir($assets) && !mkdir($assets, 0777, true) && !is_dir($assets)) {
    fwrite(STDERR, "Could not create docs/assets\n");
    exit(1);
}

copy($root . '/site/assets/site.css', $assets . '/site.css');
copy($root . '/site/assets/site.js', $assets . '/site.js');
file_put_contents($docs . '/.nojekyll', '');

$pages = [
    'index' => [
        'file' => 'index.html',
        'template' => 'index.php',
        'title_en' => 'miGears — pick the pieces, not the pile',
        'title_zh' => 'miGears — 选对模块，而不是全装',
        'description' => 'Module map, selection guide, and ready-made stacks for the miGears PHP framework.',
    ],
    'modules' => [
        'file' => 'modules.html',
        'template' => 'modules.php',
        'title_en' => 'miGears — module map',
        'title_zh' => 'miGears — 模块地图',
        'description' => 'All 30 miGears modules by layer, with when to use and when to avoid each one.',
    ],
    'choose' => [
        'file' => 'choose.html',
        'template' => 'choose.php',
        'title_en' => 'miGears — selection guide',
        'title_zh' => 'miGears — 选型指南',
        'description' => 'Four questions that lead to a miGears stack.',
    ],
    'stacks' => [
        'file' => 'stacks.html',
        'template' => 'stacks.php',
        'title_en' => 'miGears — ready-made stacks',
        'title_zh' => 'miGears — 预制组合',
        'description' => 'Four miGears stacks with composer commands and removal advice.',
    ],
];

$nav = [
    'index' => ['en' => 'Overview', 'zh' => '概览'],
    'modules' => ['en' => 'Modules', 'zh' => '模块'],
    'choose' => ['en' => 'Choose', 'zh' => '选型'],
    'stacks' => ['en' => 'Stacks', 'zh' => '组合'],
];

$shared = [
    'modules' => $modules,
    'modulesByLayer' => $modulesByLayer,
    'layers' => $layers,
    'recipes' => $recipes,
    'recipesDoc' => $recipesDoc,
    'modulesDoc' => $modulesDoc,
    'repo' => $repo,
    'version' => $version,
    'nav' => $nav,
];

foreach ($pages as $key => $page) {
    $content = renderTemplate(
        $root . '/site/templates/' . $page['template'],
        $shared + ['active' => $key]
    );

    $html = renderTemplate($root . '/site/templates/layout.php', $shared + [
        'active' => $key,
        'content' => $content,
        'title_en' => $page['title_en'],
        'title_zh' => $page['title_zh'],
        'description' => $page['description'],
    ]);

    file_put_contents($docs . '/' . $page['file'], $html);
}

printf("Built %d pages into docs/.\n", count($pages));
