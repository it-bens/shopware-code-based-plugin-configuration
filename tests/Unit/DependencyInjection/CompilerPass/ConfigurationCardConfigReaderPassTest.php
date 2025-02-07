<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit\DependencyInjection\CompilerPass;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProviderInterface;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\CompilerPass\ConfigurationCardConfigReaderPass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\System\SystemConfig\Util\ConfigReader as BundleXmlConfigReader;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

#[CoversClass(ConfigurationCardConfigReaderPass::class)]
final class ConfigurationCardConfigReaderPassTest extends TestCase
{
    public static function processAfterAlreadyRunProvider(): \Generator
    {
        $configurationCardConfigReaderPass = new ConfigurationCardConfigReaderPass();
        $container = new ContainerBuilder();

        $container->addDefinitions([
            ConfigurationCardProviderProvider::class => new Definition(ConfigurationCardProviderProvider::class),
            ConfigurationCardConfigReader::class => new Definition(ConfigurationCardConfigReader::class),
        ]);
        $container->addAliases([
            ConfigurationCardProviderProviderInterface::class => new Alias(ConfigurationCardProviderProvider::class),
            BundleXmlConfigReader::class => new Alias(ConfigurationCardConfigReader::class),
        ]);

        yield [$configurationCardConfigReaderPass, $container];
    }

    public static function processAfterInvalidRunProvider(): \Generator
    {
        $configurationCardConfigReaderPass = new ConfigurationCardConfigReaderPass();
        $container = new ContainerBuilder();

        $container->addDefinitions([
            ConfigurationCardProviderProvider::class => new Definition(ConfigurationCardProviderProvider::class),
        ]);

        yield 'with ConfigurationCardProviderProvider definition' => [
            $configurationCardConfigReaderPass,
            $container,
            \LogicException::class,
            sprintf(
                'The compiler contains a definition for %s but not the alias for %s.',
                ConfigurationCardProviderProvider::class,
                ConfigurationCardProviderProviderInterface::class
            ),
        ];

        $container = new ContainerBuilder();

        $container->addDefinitions([
            ConfigurationCardProviderProvider::class => new Definition(ConfigurationCardProviderProvider::class),
        ]);
        $container->addAliases([
            ConfigurationCardProviderProviderInterface::class => new Alias(ConfigurationCardProviderProvider::class),
        ]);

        yield 'with ConfigurationCardProviderProvider definition and ConfigurationCardProviderProviderInterface alias' => [
            $configurationCardConfigReaderPass,
            $container,
            \LogicException::class,
            sprintf('The compiler contains no definition for %s.', ConfigurationCardConfigReader::class),
        ];

        $container = new ContainerBuilder();

        $container->addDefinitions([
            ConfigurationCardProviderProvider::class => new Definition(ConfigurationCardProviderProvider::class),
            ConfigurationCardConfigReader::class => new Definition(ConfigurationCardConfigReader::class),
        ]);
        $container->addAliases([
            ConfigurationCardProviderProviderInterface::class => new Alias(ConfigurationCardProviderProvider::class),
        ]);

        yield 'with ConfigurationCardProviderProvider definition, ConfigurationCardConfigReader definition and ConfigurationCardProviderProviderInterface alias' => [
            $configurationCardConfigReaderPass,
            $container,
            \LogicException::class,
            sprintf(
                'The compiler contains a definition for %s but not the alias for %s.',
                ConfigurationCardConfigReader::class,
                BundleXmlConfigReader::class
            ),
        ];

        $container = new ContainerBuilder();

        $container->addDefinitions([
            ConfigurationCardProviderProvider::class => new Definition(ConfigurationCardProviderProvider::class),
            ConfigurationCardConfigReader::class => new Definition(ConfigurationCardConfigReader::class),
        ]);
        $container->addAliases([
            ConfigurationCardProviderProviderInterface::class => new Alias(ConfigurationCardProviderProvider::class),
            BundleXmlConfigReader::class => new Alias('test'),
        ]);

        yield 'with ConfigurationCardProviderProvider definition, ConfigurationCardConfigReader definition, ConfigurationCardProviderProviderInterface alias and invalid BundleXmlConfigReader alias' => [
            $configurationCardConfigReaderPass,
            $container,
            \LogicException::class,
            sprintf(
                'The compiler contains the alias %s but it does not point to %s.',
                BundleXmlConfigReader::class,
                ConfigurationCardConfigReader::class
            ),
        ];
    }

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

    #[DataProvider('processAfterAlreadyRunProvider')]
    public function testProcessAfterAlreadyRun(
        ConfigurationCardConfigReaderPass $configurationCardConfigReaderPass,
        ContainerBuilder $container
    ): void {
        $configurationCardConfigReaderPass->process($container);

        $configurationCardConfigReaderDefinition = $container->getDefinition(ConfigurationCardConfigReader::class);
        $this->assertArrayNotHasKey('$bundleXmlConfigReader', $configurationCardConfigReaderDefinition->getArguments());
    }

    /**
     * @param class-string<\Throwable> $expectedException
     */
    #[DataProvider('processAfterInvalidRunProvider')]
    public function testProcessAfterInvalidRun(
        ConfigurationCardConfigReaderPass $configurationCardConfigReaderPass,
        ContainerBuilder $container,
        string $expectedException,
        string $expectedExceptionMessage
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $configurationCardConfigReaderPass->process($container);
    }
}
