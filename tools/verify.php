<?php

declare(strict_types=1);

/**
 * Validate the entry repository's metadata.
 *
 * Internal checks always run. When the sibling module directories are present
 * (the workspace layout), each module's composer.json is compared as well.
 */

$root = dirname(__DIR__);
$errors = [];

$modulesDoc = yaml_parse_file($root . '/modules.yaml');
$recipesDoc = yaml_parse_file($root . '/recipes.yaml');

if (!is_array($modulesDoc)) {
    fwrite(STDERR, "modules.yaml could not be parsed\n");
    exit(1);
}

if (!is_array($recipesDoc)) {
    fwrite(STDERR, "recipes.yaml could not be parsed\n");
    exit(1);
}

$fail = static function (string $message) use (&$errors): void {
    $errors[] = $message;
};

$isBilingual = static function (mixed $value): bool {
    return is_array($value)
        && isset($value['en'], $value['zh'])
        && is_string($value['en'])
        && is_string($value['zh'])
        && $value['en'] !== ''
        && $value['zh'] !== '';
};

$isBilingualList = static function (mixed $value) use ($isBilingual): bool {
    if (!is_array($value) || !isset($value['en'], $value['zh'])) {
        return false;
    }

    foreach (['en', 'zh'] as $lang) {
        if (!is_array($value[$lang]) || $value[$lang] === []) {
            return false;
        }

        foreach ($value[$lang] as $line) {
            if (!is_string($line) || $line === '') {
                return false;
            }
        }
    }

    return true;
};

if (($modulesDoc['schema'] ?? null) !== 1) {
    $fail('modules.yaml: schema must be 1');
}

$layerIds = [];
foreach ($modulesDoc['layers'] ?? [] as $layer) {
    if (!isset($layer['id']) || !is_string($layer['id'])) {
        $fail('modules.yaml: every layer needs a string id');
        continue;
    }

    if (isset($layerIds[$layer['id']])) {
        $fail("modules.yaml: duplicate layer id {$layer['id']}");
    }

    $layerIds[$layer['id']] = true;

    if (!isset($layer['label']) || !$isBilingual($layer['label'])) {
        $fail("modules.yaml: layer {$layer['id']} needs a bilingual label");
    }

    if (!isset($layer['purpose']) || !$isBilingual($layer['purpose'])) {
        $fail("modules.yaml: layer {$layer['id']} needs a bilingual purpose");
    }
}

$modules = [];
$packages = [];

foreach ($modulesDoc['modules'] ?? [] as $module) {
    $id = $module['id'] ?? null;
    $package = $module['package'] ?? null;

    if (!is_string($id) || $id === '') {
        $fail('modules.yaml: a module has no id');
        continue;
    }

    if (isset($modules[$id])) {
        $fail("modules.yaml: duplicate module id {$id}");
    }

    if (!is_string($package) || !str_starts_with($package, 'migears/')) {
        $fail("modules.yaml: module {$id} has an invalid package name");
        continue;
    }

    if (isset($packages[$package])) {
        $fail("modules.yaml: duplicate package {$package}");
    }

    $modules[$id] = $module;
    $packages[$package] = $id;

    if (!isset($module['layer'], $layerIds[$module['layer']])) {
        $fail("modules.yaml: module {$id} references an unknown layer");
    }

    foreach (['repo', 'readme', 'dir'] as $key) {
        if (!isset($module[$key]) || !is_string($module[$key]) || $module[$key] === '') {
            $fail("modules.yaml: module {$id} needs a non-empty {$key}");
        }
    }

    if (!array_key_exists('published', $module)) {
        $fail("modules.yaml: module {$id} needs a published field (version or null)");
    } elseif ($module['published'] !== null && !is_string($module['published'])) {
        $fail("modules.yaml: module {$id} published must be a string or null");
    }

    if (!isset($module['summary']) || !$isBilingual($module['summary'])) {
        $fail("modules.yaml: module {$id} needs a bilingual summary");
    }

    if (!isset($module['use_when']) || !$isBilingualList($module['use_when'])) {
        $fail("modules.yaml: module {$id} needs bilingual use_when lists");
    }

    if (!isset($module['avoid_when']) || !$isBilingualList($module['avoid_when'])) {
        $fail("modules.yaml: module {$id} needs bilingual avoid_when lists");
    }

    if (isset($module['note']) && !$isBilingual($module['note'])) {
        $fail("modules.yaml: module {$id} has an invalid note");
    }

    if (!isset($module['requires']) || !is_array($module['requires'])) {
        $fail("modules.yaml: module {$id} needs a requires list");
    }
}

foreach ($modules as $id => $module) {
    foreach ($module['requires'] ?? [] as $required) {
        if (!is_string($required) || !isset($modules[$required])) {
            $fail("modules.yaml: module {$id} requires unknown module " . var_export($required, true));
        }
    }
}

$ruleIds = [];

foreach ($recipesDoc['hard_rules'] ?? [] as $rule) {
    $id = $rule['id'] ?? null;

    if (!is_string($id) || $id === '') {
        $fail('recipes.yaml: a hard rule has no id');
        continue;
    }

    if (isset($ruleIds[$id])) {
        $fail("recipes.yaml: duplicate hard rule id {$id}");
    }

    $ruleIds[$id] = true;

    if (!isset($rule['en'], $rule['zh']) || !is_string($rule['en']) || !is_string($rule['zh'])) {
        $fail("recipes.yaml: hard rule {$id} must be bilingual");
    }
}

$recipeIds = [];

foreach ($recipesDoc['recipes'] ?? [] as $recipe) {
    $id = $recipe['id'] ?? null;

    if (!is_string($id) || $id === '') {
        $fail('recipes.yaml: a recipe has no id');
        continue;
    }

    if (isset($recipeIds[$id])) {
        $fail("recipes.yaml: duplicate recipe id {$id}");
    }

    $recipeIds[$id] = true;

    foreach (['name', 'tagline', 'use_when', 'avoid_when', 'remove_if'] as $key) {
        if (!isset($recipe[$key])) {
            $fail("recipes.yaml: recipe {$id} needs {$key}");
            continue;
        }

        $valid = in_array($key, ['name', 'tagline'], true)
            ? $isBilingual($recipe[$key])
            : $isBilingualList($recipe[$key]);

        if (!$valid) {
            $fail("recipes.yaml: recipe {$id} has an invalid {$key}");
        }
    }

    if (isset($recipe['notes']) && !$isBilingualList($recipe['notes'])) {
        $fail("recipes.yaml: recipe {$id} has invalid notes");
    }

    if (isset($recipe['page_frontends'])) {
        $frontends = $recipe['page_frontends'];

        if (!isset($frontends['label']) || !$isBilingual($frontends['label'])) {
            $fail("recipes.yaml: recipe {$id} page_frontends needs a bilingual label");
        }

        if (!isset($frontends['note']) || !$isBilingual($frontends['note'])) {
            $fail("recipes.yaml: recipe {$id} page_frontends needs a bilingual note");
        }

        $options = $frontends['options'] ?? null;
        if (!is_array($options) || $options === []) {
            $fail("recipes.yaml: recipe {$id} page_frontends needs a non-empty options list");
        } else {
            foreach ($options as $option) {
                if (!is_string($option) || !isset($modules[$option])) {
                    $fail("recipes.yaml: recipe {$id} page_frontends references unknown module " . var_export($option, true));
                }
            }

            if (!isset($frontends['default']) || !in_array($frontends['default'], $options, true)) {
                $fail("recipes.yaml: recipe {$id} page_frontends default must be one of its options");
            }
        }
    }

    $direct = [];
    foreach ($recipe['modules'] ?? [] as $moduleId) {
        if (!is_string($moduleId) || !isset($modules[$moduleId])) {
            $fail("recipes.yaml: recipe {$id} references unknown module " . var_export($moduleId, true));
            continue;
        }

        $direct[] = $modules[$moduleId]['package'];
    }

    foreach (['pulled_in', 'optional_modules'] as $key) {
        foreach ($recipe[$key] ?? [] as $moduleId) {
            if (!is_string($moduleId) || !isset($modules[$moduleId])) {
                $fail("recipes.yaml: recipe {$id} {$key} references unknown module " . var_export($moduleId, true));
            }
        }
    }

    $declared = $recipe['packages'] ?? [];
    sort($direct);
    $sortedDeclared = $declared;
    sort($sortedDeclared);

    if ($direct !== $sortedDeclared) {
        $fail("recipes.yaml: recipe {$id} packages do not match its modules");
    }

    $composer = $recipe['composer'] ?? '';
    foreach ($declared as $package) {
        if (!is_string($composer) || !str_contains($composer, $package)) {
            $fail("recipes.yaml: recipe {$id} composer command is missing {$package}");
        }
    }

    $starter = $recipe['starter'] ?? null;
    if ($starter !== null) {
        if (!is_string($starter) || !is_dir($root . '/' . $starter)) {
            $fail("recipes.yaml: recipe {$id} points at a missing starter");
        }
    }
}

$workspace = getenv('MIGEARS_WORKSPACE') ?: dirname($root);
$checked = 0;

foreach ($modules as $id => $module) {
    $dir = $workspace . '/' . $module['dir'];

    if (!is_dir($dir)) {
        continue;
    }

    $composerFile = $dir . '/composer.json';
    if (!is_file($composerFile)) {
        $fail("workspace: {$id} has no composer.json");
        continue;
    }

    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $fail("workspace: {$id} composer.json is not valid JSON");
        continue;
    }

    $checked++;

    if (($composer['name'] ?? null) !== $module['package']) {
        $fail("workspace: {$id} package is " . var_export($composer['name'] ?? null, true) . ", expected {$module['package']}");
    }

    if (($composer['version'] ?? null) !== ($modulesDoc['version'] ?? null)) {
        $fail("workspace: {$id} version is " . var_export($composer['version'] ?? null, true) . ", expected " . var_export($modulesDoc['version'] ?? null, true));
    }

    $requires = [];
    foreach (array_keys($composer['require'] ?? []) as $package) {
        if (is_string($package) && str_starts_with($package, 'migears/')) {
            if (!isset($packages[$package])) {
                $fail("workspace: {$id} requires {$package}, which is missing from modules.yaml");
                continue;
            }

            $requires[] = $packages[$package];
        }
    }

    $declaredRequires = $module['requires'] ?? [];
    sort($requires);
    sort($declaredRequires);

    if ($requires !== $declaredRequires) {
        $fail("workspace: {$id} requires do not match modules.yaml");
    }
}

if ($errors !== []) {
    fwrite(STDERR, "Metadata verification failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, "  - {$error}\n");
    }
    exit(1);
}

printf(
    "Verified %d modules, %d layers, %d recipes, %d hard rules; %d workspace modules compared.\n",
    count($modules),
    count($layerIds),
    count($recipeIds),
    count($ruleIds),
    $checked
);
