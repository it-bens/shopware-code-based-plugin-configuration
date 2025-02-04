<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCard;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProviderInterface;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\BoolField;
use ITB\ShopwareCodeBasedPluginConfiguration\GeneralFieldInformation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Bundle;
use Shopware\Core\System\SystemConfig\Util\ConfigReader as BundleXmlConfigReader;

#[CoversClass(ConfigurationCardConfigReader::class)]
final class ConfigurationCardConfigReaderTest extends TestCase
{
    public static function constructionProvider(): \Generator
    {
        $bundleXmlConfigReader = self::createStub(BundleXmlConfigReader::class);
        $configurationCardProviderProvider = self::createStub(ConfigurationCardProviderProviderInterface::class);
        yield [$bundleXmlConfigReader, $configurationCardProviderProvider];
    }

    public static function getConfigFromBundleProvider(): \Generator
    {
        $bundleXmlConfigReader = self::createStub(BundleXmlConfigReader::class);
        $bundleXmlConfigReader->method('getConfigFromBundle')
            ->willReturn([]);

        $configurationCardProviderProvider = self::createStub(ConfigurationCardProviderProviderInterface::class);
        $configurationCardProviderProvider->method('getConfigurationCardProviders')
            ->willReturn([]);

        $configReader = new ConfigurationCardConfigReader($bundleXmlConfigReader, $configurationCardProviderProvider);
        $bundle = self::createStub(Bundle::class);
        yield 'empty decorated bundle config and empty configuration card provider list' => [$configReader, $bundle, []];

        $bundleXmlConfigReader = self::createStub(BundleXmlConfigReader::class);
        $bundleXmlConfigReader->method('getConfigFromBundle')
            ->willReturn([
                'config' => 'value',
            ]);
        $configReader = new ConfigurationCardConfigReader($bundleXmlConfigReader, $configurationCardProviderProvider);
        yield 'not empty decorated bundle config and empty configuration card provider list' => [
            $configReader, $bundle, [
                'config' => 'value',
            ]];

        $configurationCardProvider = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider->method('getBundleClasses')
            ->willReturn(['SomeRandomClass']);
        $configurationCardProviderProvider = self::createStub(ConfigurationCardProviderProviderInterface::class);
        $configurationCardProviderProvider->method('getConfigurationCardProviders')
            ->willReturn([$configurationCardProvider]);

        $configReader = new ConfigurationCardConfigReader($bundleXmlConfigReader, $configurationCardProviderProvider);
        yield "not empty decorated bundle config and not empty configuration card provider list but the providers bundle classes doesn't match the bundle class" => [
            $configReader, $bundle, [
                'config' => 'value',
            ]];

        $configurationCardProvider = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider->method('getBundleClasses')
            ->willReturn([Bundle::class]);

        $generalInformation = new GeneralFieldInformation('field_1', 'Field 1', 'Feld 1', null, null);
        $boolField = new BoolField($generalInformation, true);
        $configurationCard = new ConfigurationCard('Card 1', 'Karte 1', [$boolField]);
        $configurationCardProvider->method('getConfigurationCards')
            ->willReturn([$configurationCard]);

        $configurationCardProviderProvider = self::createStub(ConfigurationCardProviderProviderInterface::class);
        $configurationCardProviderProvider->method('getConfigurationCardProviders')
            ->willReturn([$configurationCardProvider]);
        $configReader = new ConfigurationCardConfigReader($bundleXmlConfigReader, $configurationCardProviderProvider);
        yield 'not empty decorated bundle config and not empty configuration card provider list and the providers bundle classes match the bundle class' => [
            $configReader, $bundle, [
                'config' => 'value',
                [
                    'title' => [
                        'en-GB' => 'Card 1',
                        'de-DE' => 'Karte 1',
                    ],
                    'name' => null,
                    'elements' => [
                        [
                            'name' => 'field_1',
                            'label' => [
                                'en-GB' => 'Field 1',
                                'de-DE' => 'Feld 1',
                            ],
                            'helpText' => [
                                'en-GB' => null,
                                'de-DE' => null,
                            ],
                            'type' => 'bool',
                            'defaultValue' => 'true',
                        ],
                    ],
                ],
            ]];
    }

    #[DataProvider('constructionProvider')]
    public function testConstruction(
        BundleXmlConfigReader $bundleXmlConfigReader,
        ConfigurationCardProviderProviderInterface $configurationCardProviderProvider
    ): void {
        $configReader = new ConfigurationCardConfigReader($bundleXmlConfigReader, $configurationCardProviderProvider);
        $this->assertInstanceOf(ConfigurationCardConfigReader::class, $configReader);
    }

    /**
     * @phpstan-ignore-next-line
     */
    #[DataProvider('getConfigFromBundleProvider')]
    public function testGetConfigFromBundle(ConfigurationCardConfigReader $configReader, Bundle $bundle, array $expectedConfig): void
    {
        $this->assertSame($expectedConfig, $configReader->getConfigFromBundle($bundle));
    }
}
