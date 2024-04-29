<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;

interface ConfigurationCardProviderProviderInterface
{
    /**
     * @return iterable<ConfigurationCardProvider>
     */
    public function getConfigurationCardProviders(): iterable;
}
