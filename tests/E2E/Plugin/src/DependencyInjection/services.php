<?php

declare(strict_types=1);

use ITB\ShopwareCodeBasedPluginConfigurationTestPlugin\TestConfigurationCardProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(TestConfigurationCardProvider::class);
};
