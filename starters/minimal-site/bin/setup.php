<?php

declare(strict_types=1);

/**
 * Prepare the starter's module sources, then install dependencies.
 *
 * In the miGears workspace the sibling module repositories already exist, so
 * they are linked. Anywhere else they are cloned from GitHub at their default
 * branch. Nothing is copied, so a linked module is the same tree the module
 * owner works on.
 */

$root = dirname(__DIR__);
$modulesDir = $root . '/.modules';
$workspace = dirname(dirname(dirname($root)));

$modules = [
    'migears-web',
    'migears-template',
    'migears-log',
    'migears-debug',
    'migears-http-exceptions',
];

if (!is_dir($modulesDir) && !mkdir($modulesDir, 0777, true) && !is_dir($modulesDir)) {
    fwrite(STDERR, "Could not create {$modulesDir}\n");
    exit(1);
}

foreach ($modules as $module) {
    $target = $modulesDir . '/' . $module;

    if (!is_dir($target)) {
        $sibling = $workspace . '/' . $module;

        if (is_dir($sibling)) {
            if (!symlink($sibling, $target)) {
                fwrite(STDERR, "Could not link {$sibling} to {$target}\n");
                exit(1);
            }

            fwrite(STDOUT, "Linked {$module} from the workspace\n");
        } else {
            $command = 'git clone --depth 1 '
                . escapeshellarg('https://github.com/samxxu/' . $module . '.git')
                . ' '
                . escapeshellarg($target);

            fwrite(STDOUT, "Cloning {$module}\n");
            passthru($command, $code);

            if ($code !== 0) {
                fwrite(STDERR, "git clone failed for {$module}\n");
                exit($code);
            }
        }
    } else {
        fwrite(STDOUT, "Using existing {$module}\n");
    }

    $composer = json_decode((string) file_get_contents($target . '/composer.json'), true);
    $version = is_array($composer) ? (string) ($composer['version'] ?? '') : '';

    if (version_compare($version, '2.3.0', '<')) {
        fwrite(STDERR, "{$module} is {$version}, but this starter needs 2.3.0 or newer.\n");
        fwrite(STDERR, "The public repositories may lag until the current test round is released. Run this starter inside the miGears workspace, or retry after the release.\n");
        exit(1);
    }
}

fwrite(STDOUT, "Installing dependencies\n");
passthru('composer install --no-interaction', $code);

exit($code);
