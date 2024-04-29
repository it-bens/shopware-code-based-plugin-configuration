<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;

final class ConfigurationCardProviderProvider implements ConfigurationCardProviderProviderInterface
{
    /**
     * @param iterable<ConfigurationCardProvider> $configurationCardProviders
     */
    public function __construct(
        private readonly iterable $configurationCardProviders
    ) {
    }

    public function getConfigurationCardProviders(): iterable
    {
        $configurationCardProviders = [...$this->configurationCardProviders];

        uasort(
            $configurationCardProviders,
            static fn (ConfigurationCardProvider $a, ConfigurationCardProvider $b): int => $b->getPriority() <=> $a->getPriority()
        );

        return array_values($configurationCardProviders);
    }
}
