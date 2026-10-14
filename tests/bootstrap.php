<?php

declare(strict_types=1);

/*
 * Minimal PSR-4 autoloader.
 *
 * PHPUnit is installed as a PHAR, so the project has no Composer autoloader!
 * This file is referenced by the "bootstrap" attribute in phpunit.xml and maps
 *
 *   App\Tests\...  ->  tests/...
 *   App\...        ->  src/...
 */
spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'App\\Tests\\' => __DIR__ . '/',
        'App\\' => dirname(__DIR__) . '/src/',
    ];

    foreach ($prefixes as $prefix => $baseDirectory) {
        if (!str_starts_with($class, $prefix)) {
            continue;
        }

        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDirectory . str_replace('\\', '/', $relativeClass) . '.php';

        if (is_file($file)) {
            require $file;
        }

        return;
    }
});
