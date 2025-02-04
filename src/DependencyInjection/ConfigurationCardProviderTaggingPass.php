<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection;

use ITB\ShopwareCodeBasedPluginConfiguration\Attribute\AsConfigurationCardProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ConfigurationCardProviderTaggingPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container->registerAttributeForAutoconfiguration(
            AsConfigurationCardProvider::class,
            static function (ChildDefinition $definition, AsConfigurationCardProvider $attribute, \ReflectionClass $reflector): void {
                if (! $reflector->implementsInterface(ConfigurationCardProvider::class)) {
                    throw new \RuntimeException(sprintf(
                        'The class %s must implement %s to be used as a configuration card provider. The `AsConfigurationCardProvider` attribute cannot be used here.',
                        $reflector->getName(),
                        ConfigurationCardProvider::class
                    ));
                }

                $definition->addTag(Tags::CONFIGURATION_CARD_PROVIDER_TAG, [
                    'priority' => $attribute->priority,
                ]);
            }
        );
    }
}
