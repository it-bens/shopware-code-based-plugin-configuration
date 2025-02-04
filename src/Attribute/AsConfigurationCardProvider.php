<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Attribute;

/**
 * Service tag to autoconfigure config card providers.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class AsConfigurationCardProvider
{
    public function __construct(
        public int $priority = 0
    ) {
    }
}
