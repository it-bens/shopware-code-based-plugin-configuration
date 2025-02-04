<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration;

use Shopware\Core\Framework\Bundle;

interface ConfigurationCardProvider
{
    /**
     * @return class-string<Bundle>[]
     */
    public function getBundleClasses(): array;

    /**
     * @return ConfigurationCard[]
     */
    public function getConfigurationCards(): array;
}
