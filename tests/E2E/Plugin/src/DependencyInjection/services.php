<?php

declare(strict_types=1);

use ITB\ShopwareCodeBasedPluginConfiguration\Test\E2E\Plugin\TestConfigurationCardProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(TestConfigurationCardProvider::class);
};
