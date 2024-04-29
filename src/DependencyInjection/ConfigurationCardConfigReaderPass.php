<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProviderInterface;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;
use Shopware\Core\System\SystemConfig\Util\ConfigReader as BundleXmlConfigReader;
use Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class ConfigurationCardConfigReaderPass implements CompilerPassInterface
{
    public const CONFIGURATION_CARD_PROVIDER_TAG = 'itb.shopware_code_based_plugin_configuration.configuration_card_provider';

    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $id => $definition) {
            $class = $definition->getClass();
            if ($class === null) {
                continue;
            }

            if (is_subclass_of($class, ConfigurationCardProvider::class)) {
                $definition->addTag(self::CONFIGURATION_CARD_PROVIDER_TAG);
                $container->setDefinition($id, $definition);
            }
        }

        $configurationCardProviderDefinitions = [];
        foreach (array_keys($container->findTaggedServiceIds(self::CONFIGURATION_CARD_PROVIDER_TAG)) as $id) {
            $configurationCardProviderDefinitions[] = new Reference($id);
        }

        $configurationCardProviderProviderDefinition = new Definition(ConfigurationCardProviderProvider::class);
        $configurationCardProviderProviderDefinition->setArgument(
            '$configurationCardProviders',
            new IteratorArgument($configurationCardProviderDefinitions)
        );

        $container->addDefinitions([
            ConfigurationCardProviderProvider::class => $configurationCardProviderProviderDefinition,
        ]);
        $container->setAlias(ConfigurationCardProviderProviderInterface::class, ConfigurationCardProviderProvider::class);

        // BundleXmlConfigReader decoration

        $bundleXmlConfigReaderDefinition = $container->getDefinition(BundleXmlConfigReader::class);
        $isBundleXmlConfigReaderDefinitionPublic = $bundleXmlConfigReaderDefinition->isPublic();
        $bundleXmlConfigReaderDefinition->setPublic(false);
        $container->setDefinition(BundleXmlConfigReader::class . '.inner', $bundleXmlConfigReaderDefinition);

        $configurationCardConfigReaderDefinition = new Definition(ConfigurationCardConfigReader::class);
        $configurationCardConfigReaderDefinition->setDecoratedService(BundleXmlConfigReader::class);
        $configurationCardConfigReaderDefinition->setArgument(
            '$bundleXmlConfigReader',
            new Reference(BundleXmlConfigReader::class . '.inner')
        );
        $configurationCardConfigReaderDefinition->setArgument(
            '$configurationCardProviderProvider',
            new Reference(ConfigurationCardProviderProviderInterface::class)
        );
        $container->addDefinitions([
            ConfigurationCardConfigReader::class => $configurationCardConfigReaderDefinition,
        ]);
        $container->setAlias(BundleXmlConfigReader::class, ConfigurationCardConfigReader::class)->setPublic(
            $isBundleXmlConfigReaderDefinitionPublic
        );
    }
}
