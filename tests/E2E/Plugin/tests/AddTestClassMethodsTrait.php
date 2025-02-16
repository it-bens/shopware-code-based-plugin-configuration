<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfigurationTestPlugin\Test;

trait AddTestClassMethodsTrait
{
    /**
     * This method is required because the traits are normally used in test classes.
     */
    private static function getName(bool $withDataSet = true): string
    {
        return self::class;
    }
}
