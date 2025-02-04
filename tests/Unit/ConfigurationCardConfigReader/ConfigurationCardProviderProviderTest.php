<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit\ConfigurationCardConfigReader;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigurationCardProviderProvider::class)]
final class ConfigurationCardProviderProviderTest extends TestCase
{
    public static function constructionProvider(): \Generator
    {
        $configurationCardProvider = self::createStub(ConfigurationCardProvider::class);

        yield [[$configurationCardProvider]];
    }

    public static function getBundleClassesProvider(): \Generator
    {
        $configurationCardProviderProvider = new ConfigurationCardProviderProvider([]);
        yield 'without configuration card providers' => [$configurationCardProviderProvider, []];

        $configurationCardProvider1 = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider1->method('getBundleClasses')
            ->willReturn(['BundleClass1', 'BundleClass2']);
        $configurationCardProvider2 = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider2->method('getBundleClasses')
            ->willReturn(['BundleClass3', 'BundleClass4']);

        $configurationCardProviderProvider = new ConfigurationCardProviderProvider([
            $configurationCardProvider1,
            $configurationCardProvider2,
        ]);
        yield 'with two configuration card providers with different bundle classes' => [
            $configurationCardProviderProvider,
            ['BundleClass1', 'BundleClass2', 'BundleClass3', 'BundleClass4'],
        ];

        $configurationCardProvider3 = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider3->method('getBundleClasses')
            ->willReturn(['BundleClass1', 'BundleClass2']);
        $configurationCardProvider4 = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider4->method('getBundleClasses')
            ->willReturn(['BundleClass2', 'BundleClass3']);

        $configurationCardProviderProvider = new ConfigurationCardProviderProvider([
            $configurationCardProvider3,
            $configurationCardProvider4,
        ]);
        yield 'with two configuration card providers with partly overlapping bundle classes' => [
            $configurationCardProviderProvider,
            ['BundleClass1', 'BundleClass2', 'BundleClass3'],
        ];

        $configurationCardProvider5 = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider5->method('getBundleClasses')
            ->willReturn(['BundleClass1', 'BundleClass2']);
        $configurationCardProvider6 = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider6->method('getBundleClasses')
            ->willReturn(['BundleClass1', 'BundleClass2']);

        $configurationCardProviderProvider = new ConfigurationCardProviderProvider([
            $configurationCardProvider5,
            $configurationCardProvider6,
        ]);
        yield 'with two configuration card providers with overlapping bundle classes' => [
            $configurationCardProviderProvider,
            ['BundleClass1', 'BundleClass2'],
        ];
    }

    public static function getConfigurationCardProvidersProvider(): \Generator
    {
        $configurationCardProviderProvider = new ConfigurationCardProviderProvider([]);
        yield 'without configuration card providers' => [$configurationCardProviderProvider, []];

        $configurationCardProvider1 = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider2 = self::createStub(ConfigurationCardProvider::class);

        $configurationCardProviderProvider = new ConfigurationCardProviderProvider([
            $configurationCardProvider1,
            $configurationCardProvider2,
        ]);
        yield 'with two configuration card providers' => [
            $configurationCardProviderProvider,
            [$configurationCardProvider1, $configurationCardProvider2],
        ];
    }

    /**
     * @param ConfigurationCardProvider[] $configurationCardProviders
     */
    #[DataProvider('constructionProvider')]
    public function testConstruction(array $configurationCardProviders): void
    {
        $configurationCardProviderProvider = new ConfigurationCardProviderProvider($configurationCardProviders);
        $this->assertInstanceOf(ConfigurationCardProviderProvider::class, $configurationCardProviderProvider);
    }

    /**
     * @param string[] $expectedBundleClasses
     */
    #[DataProvider('getBundleClassesProvider')]
    public function testGetBundleClasses(
        ConfigurationCardProviderProvider $configurationCardProviderProvider,
        array $expectedBundleClasses
    ): void {
        $bundleClasses = $configurationCardProviderProvider->getBundleClasses();
        $this->assertSame($expectedBundleClasses, $bundleClasses);
    }

    /**
     * @param ConfigurationCardProvider[] $expectedConfigurationCardProviders
     */
    #[DataProvider('getConfigurationCardProvidersProvider')]
    public function testGetConfigurationCardProviders(
        ConfigurationCardProviderProvider $configurationCardProviderProvider,
        array $expectedConfigurationCardProviders
    ): void {
        $configurationCardProviders = $configurationCardProviderProvider->getConfigurationCardProviders();
        $this->assertSame($expectedConfigurationCardProviders, $configurationCardProviders);
    }
}
