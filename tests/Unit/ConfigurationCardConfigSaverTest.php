<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProviderInterface;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigSaver;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Bundle;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Event\PluginPostActivateEvent;
use Shopware\Core\Framework\Plugin\Event\PluginPostUpdateEvent;
use Shopware\Core\Framework\Plugin\KernelPluginCollection;
use Shopware\Core\Framework\Plugin\PluginEntity;
use Shopware\Core\System\SystemConfig\SystemConfigService;

final class ConfigurationCardConfigSaverTest extends TestCase
{
    public static function saveConfigurationSkippedProvider(): \Generator
    {
        $plugin = self::createStub(Plugin::class);
        $pluginEntity = self::createStub(PluginEntity::class);
        $pluginEntity->method('getBaseClass')
            ->willReturn($plugin::class);

        $configurationCardProvider = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider->method('getBundleClasses')
            ->willReturn([]);
        $configurationCardProviderProvider = new ConfigurationCardProviderProvider([$configurationCardProvider]);

        $kernelPluginCollection = self::createStub(KernelPluginCollection::class);

        $event = self::createStub(PluginPostActivateEvent::class);
        $event->method('getPlugin')
            ->willReturn($pluginEntity);

        yield 'bundle class not defined in configuration card providers' => [
            $configurationCardProviderProvider,
            $kernelPluginCollection,
            $event,
            $plugin,
        ];

        $configurationCardProvider = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider->method('getBundleClasses')
            ->willReturn([$plugin::class]);
        $configurationCardProviderProvider = new ConfigurationCardProviderProvider([$configurationCardProvider]);

        $kernelPluginCollection = self::createStub(KernelPluginCollection::class);
        $kernelPluginCollection->method('get')
            ->willReturn(null);

        $event = self::createStub(PluginPostActivateEvent::class);
        $event->method('getPlugin')
            ->willReturn($pluginEntity);

        yield 'plugin instance not in kernel plugin collection' => [
            $configurationCardProviderProvider,
            $kernelPluginCollection,
            $event,
            $plugin,
        ];
    }

    public static function saveConfigurationSuccessfulProvider(): \Generator
    {
        $plugin = self::createStub(Plugin::class);
        $pluginEntity = self::createStub(PluginEntity::class);
        $pluginEntity->method('getBaseClass')
            ->willReturn($plugin::class);

        $configurationCardProvider = self::createStub(ConfigurationCardProvider::class);
        $configurationCardProvider->method('getBundleClasses')
            ->willReturn([$plugin::class]);
        $configurationCardProviderProvider = new ConfigurationCardProviderProvider([$configurationCardProvider]);

        $kernelPluginCollection = self::createStub(KernelPluginCollection::class);
        $kernelPluginCollection->method('get')
            ->willReturnMap([[$plugin::class, $plugin]]);

        $event = self::createStub(PluginPostActivateEvent::class);
        $event->method('getPlugin')
            ->willReturn($pluginEntity);

        yield [$configurationCardProviderProvider, $kernelPluginCollection, $event, $plugin];
    }

    public function testGetSubscribedEvents(): void
    {
        $expectedSubscribedEvents = [
            PluginPostActivateEvent::class => [['saveConfiguration', 100]],
            PluginPostUpdateEvent::class => [['saveConfiguration', 100]],
        ];

        $this->assertSame($expectedSubscribedEvents, ConfigurationCardConfigSaver::getSubscribedEvents());
    }

    #[DataProvider('saveConfigurationSkippedProvider')]
    public function testSaveConfigurationSkipped(
        ConfigurationCardProviderProviderInterface $configurationCardProviderProvider,
        KernelPluginCollection $kernelPluginCollection,
        PluginPostActivateEvent|PluginPostUpdateEvent $event,
    ): void {
        $systemConfigService = $this->createMock(SystemConfigService::class);
        $systemConfigService->expects($this->never())
            ->method('savePluginConfiguration');

        $configurationCardConfigSaver = new ConfigurationCardConfigSaver(
            $configurationCardProviderProvider,
            $kernelPluginCollection,
            $systemConfigService
        );

        $configurationCardConfigSaver->saveConfiguration($event);
    }

    #[DataProvider('saveConfigurationSuccessfulProvider')]
    public function testSaveConfigurationSuccessful(
        ConfigurationCardProviderProviderInterface $configurationCardProviderProvider,
        KernelPluginCollection $kernelPluginCollection,
        PluginPostActivateEvent|PluginPostUpdateEvent $event,
        Bundle $expectedBundle
    ): void {
        $systemConfigService = $this->createMock(SystemConfigService::class);
        $systemConfigService->method('savePluginConfiguration')
            ->willReturnCallback(function (Bundle $bundle) use ($expectedBundle): void {
                $this->assertEquals($expectedBundle, $bundle);
            });

        $configurationCardConfigSaver = new ConfigurationCardConfigSaver(
            $configurationCardProviderProvider,
            $kernelPluginCollection,
            $systemConfigService
        );

        $configurationCardConfigSaver->saveConfiguration($event);
    }
}
