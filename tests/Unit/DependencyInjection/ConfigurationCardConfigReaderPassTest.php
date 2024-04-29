<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit\DependencyInjection;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProviderInterface;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\ConfigurationCardConfigReaderPass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\System\SystemConfig\Util\ConfigReader as BundleXmlConfigReader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class ConfigurationCardConfigReaderPassTest extends TestCase
{
    public static function processProvider(): \Generator
    {
        $configurationCardConfigReaderPass = new ConfigurationCardConfigReaderPass();
        $container = new ContainerBuilder();

        $bundleXmlConfigReaderDefinition = new Definition(BundleXmlConfigReader::class);
        $container->addDefinitions([
            BundleXmlConfigReader::class => $bundleXmlConfigReaderDefinition,
        ]);

        yield 'without card configuration providers' => [$configurationCardConfigReaderPass, $container];

        $container = new ContainerBuilder();

        $bundleXmlConfigReaderDefinition = new Definition(BundleXmlConfigReader::class);
        $container->addDefinitions([
            BundleXmlConfigReader::class => $bundleXmlConfigReaderDefinition,
        ]);

        $configurationCardProviderStub = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProviderStubDefinition = new Definition($configurationCardProviderStub::class);
        $container->setDefinition($configurationCardProviderStub::class, $configurationCardProviderStubDefinition);

        yield 'with one card configuration provider' => [$configurationCardConfigReaderPass, $container];
    }

    #[DataProvider('processProvider')]
    public function testProcess(ConfigurationCardConfigReaderPass $configurationCardConfigReaderPass, ContainerBuilder $container): void
    {
        $configurationCardConfigReaderPass->process($container);
        $this->assertTrue($container->hasDefinition(ConfigurationCardProviderProvider::class));
        $this->assertTrue($container->hasAlias(ConfigurationCardProviderProviderInterface::class));

        $this->assertTrue($container->hasDefinition(ConfigurationCardConfigReader::class));
        $configurationCardConfigReaderDefinition = $container->getDefinition(ConfigurationCardConfigReader::class);
        $this->assertArrayHasKey('$bundleXmlConfigReader', $configurationCardConfigReaderDefinition->getArguments());
        $bundleXmlConfigReaderReference = $configurationCardConfigReaderDefinition->getArgument('$bundleXmlConfigReader');
        /** @phpstan-ignore-next-line  */
        $this->assertSame(BundleXmlConfigReader::class . '.inner', (string) $bundleXmlConfigReaderReference);
        $this->assertArrayHasKey('$configurationCardProviderProvider', $configurationCardConfigReaderDefinition->getArguments());
        $configurationCardProviderProviderReference = $configurationCardConfigReaderDefinition->getArgument(
            '$configurationCardProviderProvider'
        );
        /** @phpstan-ignore-next-line  */
        $this->assertSame(ConfigurationCardProviderProviderInterface::class, (string) $configurationCardProviderProviderReference);

        $this->assertTrue($container->hasDefinition(BundleXmlConfigReader::class . '.inner'));
        $bundleXmlConfigReaderInnerDefinition = $container->getDefinition(BundleXmlConfigReader::class . '.inner');
        $this->assertSame(BundleXmlConfigReader::class, $bundleXmlConfigReaderInnerDefinition->getClass());

        $this->assertTrue($container->hasAlias(BundleXmlConfigReader::class));
        $bundleXmlConfigReaderAlias = $container->getAlias(BundleXmlConfigReader::class);
        $this->assertSame(ConfigurationCardConfigReader::class, (string) $bundleXmlConfigReaderAlias);
    }
}
