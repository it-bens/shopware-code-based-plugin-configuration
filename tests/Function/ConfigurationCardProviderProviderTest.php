<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Function;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProviderInterface;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\Test\Function\Mock\ConfigurationCardProvider1;
use ITB\ShopwareCodeBasedPluginConfiguration\Test\Function\Mock\ConfigurationCardProvider2;
use ITB\ShopwareCodeBasedPluginConfiguration\Test\Function\Mock\ConfigurationCardProvider3;
use PHPUnit\Framework\TestCase;

final class ConfigurationCardProviderProviderTest extends TestCase
{
    public function test(): void
    {
        $kernel = new ITBShopwareCodeBasePluginConfigrationTestKernel([
            ConfigurationCardProvider1::class,
            ConfigurationCardProvider2::class,
        ]);
        $kernel->boot();

        $container = $kernel->getContainer();

        $configurationCardProviderProvider = $container->get(ConfigurationCardProviderProviderInterface::class);
        $this->assertInstanceOf(ConfigurationCardProviderProvider::class, $configurationCardProviderProvider);

        $configurationCardProviders = iterator_to_array($configurationCardProviderProvider->getConfigurationCardProviders());
        $this->assertCount(2, $configurationCardProviders);
        $this->assertContainsOnlyInstancesOf(ConfigurationCardProvider::class, $configurationCardProviders);

        $this->assertInstanceOf(ConfigurationCardProvider1::class, $configurationCardProviders[0]);
        $this->assertInstanceOf(ConfigurationCardProvider2::class, $configurationCardProviders[1]);
    }

    public function testWithConfigurationCardProviderThatDoesntImplementInterface(): void
    {
        $kernel = new ITBShopwareCodeBasePluginConfigrationTestKernel([ConfigurationCardProvider3::class]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf(
            'The class %s must implement %s to be used as a configuration card provider. The `AsConfigurationCardProvider` attribute cannot be used here.',
            ConfigurationCardProvider3::class,
            ConfigurationCardProvider::class
        ));
        $kernel->boot();
    }
}
