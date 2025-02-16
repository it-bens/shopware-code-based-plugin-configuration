<?php

declare(strict_types=1);

/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Shopware\Core\TestBootstrapper;

$platformRoot = dirname(__DIR__, 4);

if (is_readable($platformRoot . '/src/Core/TestBootstrapper.php')) {
    require $platformRoot . '/src/Core/TestBootstrapper.php';
} else {
    require __DIR__ . '/../vendor/shopware/core/TestBootstrapper.php';
}

$projectDir = $_SERVER['PROJECT_ROOT'];
if (is_string($projectDir) === false) {
    $projectDir = $platformRoot;
}

return (new TestBootstrapper())
    ->setProjectDir($projectDir)
    ->setForceInstallPlugins(true)
    ->addCallingPlugin()
    ->bootstrap()
    ->getClassLoader();
