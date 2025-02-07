<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\CompilerPass;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProviderInterface;
use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\CompilerPass\ConfigurationCardConfigReaderPass\ConfigurationCardProviderReferenceCollection;
use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\Tags;
use Shopware\Core\System\SystemConfig\Util\ConfigReader as BundleXmlConfigReader;
use Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class ConfigurationCardConfigReaderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($this->didCompilerPassAlreadyRun($container)) {
            return;
        }

        $configurationCardProviderReferences = new ConfigurationCardProviderReferenceCollection();
        foreach ($container->findTaggedServiceIds(Tags::CONFIGURATION_CARD_PROVIDER_TAG) as $id => $tags) {
            $configurationCardProviderReferences->add($id, $tags[0]['priority']);
        }

        $configurationCardProviderProviderDefinition = new Definition(ConfigurationCardProviderProvider::class);
        $configurationCardProviderProviderDefinition->setArgument(
            '$configurationCardProviders',
            new IteratorArgument($configurationCardProviderReferences->getReferences())
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

    private function didCompilerPassAlreadyRun(ContainerBuilder $container): bool
    {
        if ($container->hasDefinition(ConfigurationCardProviderProvider::class)) {
            if ($container->hasAlias(ConfigurationCardProviderProviderInterface::class)) {
                if ($container->hasDefinition(ConfigurationCardConfigReader::class)) {
                    if ($container->hasAlias(BundleXmlConfigReader::class)) {
                        if ((string) $container->getAlias(BundleXmlConfigReader::class) === ConfigurationCardConfigReader::class) {
                            return true;
                        }

                        throw new \LogicException(sprintf(
                            'The compiler contains the alias %s but it does not point to %s.',
                            BundleXmlConfigReader::class,
                            ConfigurationCardConfigReader::class
                        ));
                    }

                    throw new \LogicException(sprintf(
                        'The compiler contains a definition for %s but not the alias for %s.',
                        ConfigurationCardConfigReader::class,
                        BundleXmlConfigReader::class
                    ));
                }

                throw new \LogicException(sprintf('The compiler contains no definition for %s.', ConfigurationCardConfigReader::class));
            }

            throw new \LogicException(sprintf(
                'The compiler contains a definition for %s but not the alias for %s.',
                ConfigurationCardProviderProvider::class,
                ConfigurationCardProviderProviderInterface::class
            ));
        }

        return false;
    }
}
