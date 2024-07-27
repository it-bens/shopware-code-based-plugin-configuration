<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProviderInterface;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigSaver;
use Shopware\Core\Framework\Plugin\KernelPluginCollection;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class ConfigurationCardConfigSaverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $configurationCardSaverDefinition = new Definition(ConfigurationCardConfigSaver::class);
        $configurationCardSaverDefinition->setArgument(
            '$configurationCardProviderProvider',
            new Reference(ConfigurationCardProviderProviderInterface::class)
        );
        $configurationCardSaverDefinition->setArgument('$kernelPluginCollection', new Reference(KernelPluginCollection::class));
        $configurationCardSaverDefinition->setArgument('$systemConfigService', new Reference(SystemConfigService::class));
        $configurationCardSaverDefinition->addTag('kernel.event_subscriber');

        $container->addDefinitions([
            ConfigurationCardConfigSaver::class => $configurationCardSaverDefinition,
        ]);
    }
}
