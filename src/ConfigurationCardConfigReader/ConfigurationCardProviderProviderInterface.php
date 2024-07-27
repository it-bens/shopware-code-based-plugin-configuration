<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;
use Shopware\Core\Framework\Bundle;

interface ConfigurationCardProviderProviderInterface
{
    /**
     * @return class-string<Bundle>[]
     */
    public function getBundleClasses(): array;

    /**
     * @return iterable<ConfigurationCardProvider>
     */
    public function getConfigurationCardProviders(): iterable;
}
