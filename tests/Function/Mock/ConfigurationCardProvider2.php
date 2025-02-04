<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Function\Mock;

use ITB\ShopwareCodeBasedPluginConfiguration\Attribute\AsConfigurationCardProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;

#[AsConfigurationCardProvider]
final class ConfigurationCardProvider2 implements ConfigurationCardProvider
{
    public function getBundleClasses(): array
    {
        return [];
    }

    public function getConfigurationCards(): array
    {
        return [];
    }

    public function getPriority(): int
    {
        return 0;
    }
}
