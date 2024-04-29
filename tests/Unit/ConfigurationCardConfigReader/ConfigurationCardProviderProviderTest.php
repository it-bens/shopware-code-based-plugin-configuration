<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit\ConfigurationCardConfigReader;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConfigurationCardProviderProviderTest extends TestCase
{
    public static function constructionProvider(): \Generator
    {
        $configurationCardProvider = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider->method('getPriority')
            ->willReturn(0);

        yield [[$configurationCardProvider]];
    }

    public static function getConfigurationCardProvidersProvider(): \Generator
    {
        $configurationCardProviderProvider = new ConfigurationCardProviderProvider([]);
        yield 'without configuration card providers' => [$configurationCardProviderProvider, []];

        $configurationCardProvider1 = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider1->method('getPriority')
            ->willReturn(0);
        $configurationCardProvider2 = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider2->method('getPriority')
            ->willReturn(1);

        $configurationCardProviderProvider = new ConfigurationCardProviderProvider([
            $configurationCardProvider1,
            $configurationCardProvider2,
        ]);
        yield 'with two configuration card providers with different priorities' => [
            $configurationCardProviderProvider,
            [$configurationCardProvider2, $configurationCardProvider1],
        ];
    }

    #[DataProvider('constructionProvider')]
    public function testConstruction(array $configurationCardProviders): void
    {
        $configurationCardProviderProvider = new ConfigurationCardProviderProvider($configurationCardProviders);
        $this->assertInstanceOf(ConfigurationCardProviderProvider::class, $configurationCardProviderProvider);
    }

    #[DataProvider('getConfigurationCardProvidersProvider')]
    public function testGetConfigurationCardProviders(
        ConfigurationCardProviderProvider $configurationCardProviderProvider,
        array $expectedConfigurationCardProviders
    ): void {
        $configurationCardProviders = $configurationCardProviderProvider->getConfigurationCardProviders();
        $this->assertSame($expectedConfigurationCardProviders, $configurationCardProviders);
    }
}
