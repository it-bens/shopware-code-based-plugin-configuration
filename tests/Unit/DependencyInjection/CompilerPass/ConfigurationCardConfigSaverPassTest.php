<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit\DependencyInjection\CompilerPass;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProviderInterface;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigSaver;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\CompilerPass\ConfigurationCardConfigSaverPass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Plugin\KernelPluginCollection;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

#[CoversClass(ConfigurationCardConfigSaverPass::class)]
final class ConfigurationCardConfigSaverPassTest extends TestCase
{
    public static function processProvider(): \Generator
    {
        $configurationCardConfigSaverPass = new ConfigurationCardConfigSaverPass();
        $container = new ContainerBuilder();

        $configurationCardProviderStub = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProviderStubDefinition = new Definition($configurationCardProviderStub::class);
        $container->setDefinition($configurationCardProviderStub::class, $configurationCardProviderStubDefinition);

        $kernelPluginCollectionDefinition = new Definition(KernelPluginCollection::class);
        $container->addDefinitions([
            KernelPluginCollection::class => $kernelPluginCollectionDefinition,
        ]);

        $systemConfigServiceDefinition = new Definition(SystemConfigService::class);
        $container->addDefinitions([
            SystemConfigService::class => $systemConfigServiceDefinition,
        ]);

        yield 'with one card configuration provider' => [$configurationCardConfigSaverPass, $container];
    }

    #[DataProvider('processProvider')]
    public function testProcess(ConfigurationCardConfigSaverPass $configurationCardConfigSaverPass, ContainerBuilder $container): void
    {
        $configurationCardConfigSaverPass->process($container);
        $this->assertTrue($container->hasDefinition(ConfigurationCardConfigSaver::class));
        $configurationCardConfigSaverDefinition = $container->getDefinition(ConfigurationCardConfigSaver::class);

        $this->assertArrayHasKey('$configurationCardProviderProvider', $configurationCardConfigSaverDefinition->getArguments());
        $configurationCardProviderProviderArgument = $configurationCardConfigSaverDefinition->getArgument(
            '$configurationCardProviderProvider'
        );
        $this->assertInstanceOf(Reference::class, $configurationCardProviderProviderArgument);
        $this->assertSame(ConfigurationCardProviderProviderInterface::class, (string) $configurationCardProviderProviderArgument);

        $this->assertArrayHasKey('$kernelPluginCollection', $configurationCardConfigSaverDefinition->getArguments());
        $kernelPluginCollectionArgument = $configurationCardConfigSaverDefinition->getArgument('$kernelPluginCollection');
        $this->assertInstanceOf(Reference::class, $kernelPluginCollectionArgument);
        $this->assertSame(KernelPluginCollection::class, (string) $kernelPluginCollectionArgument);

        $this->assertArrayHasKey('$systemConfigService', $configurationCardConfigSaverDefinition->getArguments());
        $systemConfigServiceArgument = $configurationCardConfigSaverDefinition->getArgument('$systemConfigService');
        $this->assertInstanceOf(Reference::class, $systemConfigServiceArgument);
        $this->assertSame(SystemConfigService::class, (string) $systemConfigServiceArgument);
    }
}
