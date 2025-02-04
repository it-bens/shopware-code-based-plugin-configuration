<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Function\Mock;

use ITB\ShopwareCodeBasedPluginConfiguration\Attribute\AsConfigurationCardProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;

#[AsConfigurationCardProvider(priority: 100)]
final class ConfigurationCardProvider1 implements ConfigurationCardProvider
{
    public function getBundleClasses(): array
    {
        return [];
    }

    public function getConfigurationCards(): array
    {
        return [];
    }
}
