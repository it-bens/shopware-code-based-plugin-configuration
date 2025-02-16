<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfigurationTestPlugin\Test;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigSaver;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\System\SystemConfig\Util\ConfigReader as BundleXmlConfigReader;

final class ServiceTest extends TestCase
{
    // use AddTestClassMethodsTrait; # This is required in higher PHPUnit versions
    use IntegrationTestBehaviour;

    public function testConfigReaderReplacement(): void
    {
        $configReader = $this->getContainer()
            ->get(BundleXmlConfigReader::class);
        $this->assertInstanceOf(ConfigurationCardConfigReader::class, $configReader);
    }

    public function testConfigSaverRegistration(): void
    {
        $configSaver = $this->getContainer()
            ->get(ConfigurationCardConfigSaver::class);
        $this->assertInstanceOf(ConfigurationCardConfigSaver::class, $configSaver);
    }
}
