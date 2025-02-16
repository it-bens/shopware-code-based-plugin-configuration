<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfigurationTestPlugin\Test;

use ITB\ShopwareCodeBasedPluginConfigurationTestPlugin\TestConfigurationCardProvider;
use ITB\ShopwareCodeBasedPluginConfigurationTestPlugin\TestPlugin;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\System\SystemConfig\Util\ConfigReader;
use Shopware\Core\System\SystemConfig\Util\ConfigReader as BundleXmlConfigReader;

final class ConfigTest extends TestCase
{
    // use AddTestClassMethodsTrait; # This is required in higher PHPUnit versions
    use IntegrationTestBehaviour;

    public static function pluginConfigurationProvider(): \Generator
    {
        $configFromXml = [
            [
                'title' => [
                    'en-GB' => 'Test Plugin configuration',
                    'de-DE' => 'Test Plugin Konfiguration',
                ],
                'name' => null,
                'elements' => [],
            ],
        ];

        yield [
            'expectedPluginConfig' => array_merge($configFromXml, TestConfigurationCardProvider::getExpectedBundleConfig()),
        ];
    }

    /**
     * @dataProvider pluginConfigurationProvider
     *
     * @param array<int|string, mixed> $expectedPluginConfig
     */
    #[DataProvider('pluginConfigurationProvider')]
    public function testPluginConfiguration(array $expectedPluginConfig): void
    {
        /** @var TestPlugin $plugin */
        $plugin = $this->getContainer()
            ->get(TestPlugin::class);

        /** @var ConfigReader $configReader */
        $configReader = $this->getContainer()
            ->get(BundleXmlConfigReader::class);

        $pluginConfig = $configReader->getConfigFromBundle($plugin);
        $pluginConfigJson = json_encode($pluginConfig);
        if ($pluginConfigJson === false) {
            $this->fail('Failed to encode plugin config to JSON');
        }

        $expectedPluginConfigJson = json_encode($expectedPluginConfig);
        if ($expectedPluginConfigJson === false) {
            $this->fail('Failed to encode expected plugin config to JSON');
        }

        $this->assertJsonStringEqualsJsonString($expectedPluginConfigJson, $pluginConfigJson);
    }
}
