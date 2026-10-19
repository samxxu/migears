<?php

declare(strict_types=1);

use MiGears\Debug\DebugPage;
use MiGears\Log\Logger;
use MiGears\Template\Template;
use MiGears\Web\MiRest;
use MiGears\Web\Request;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);

// Development only. Remove this line in production.
DebugPage::register();

$rest = new MiRest(
    baseDir: $root . '/resources',
    namespace: 'App\\Resources',
);

$rest->set(
    LoggerInterface::class,
    static fn (): LoggerInterface => Logger::toFile($root . '/var/log/app.log', LogLevel::DEBUG, 'minimal-site')
);

$rest->set(
    Template::class,
    static fn (): Template => new Template($root . '/views')
);

$rest->handle(Request::fromGlobals())->send();
