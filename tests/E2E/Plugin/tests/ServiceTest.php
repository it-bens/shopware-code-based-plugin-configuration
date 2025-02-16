<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\E2E\Plugin\Test;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigSaver;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\System\SystemConfig\Util\ConfigReader as BundleXmlConfigReader;

final class ServiceTest extends TestCase
{
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
