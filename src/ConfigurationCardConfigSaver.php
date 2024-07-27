<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProviderInterface;
use Shopware\Core\Framework\Bundle;
use Shopware\Core\Framework\Plugin\Event\PluginPostActivateEvent;
use Shopware\Core\Framework\Plugin\Event\PluginPostUpdateEvent;
use Shopware\Core\Framework\Plugin\KernelPluginCollection;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ConfigurationCardConfigSaver implements EventSubscriberInterface
{
    public function __construct(
        private readonly ConfigurationCardProviderProviderInterface $configurationCardProviderProvider,
        private readonly KernelPluginCollection $kernelPluginCollection,
        private readonly SystemConfigService $systemConfigService
    ) {
    }

    /**
     * @return array<string, array{0: string, 1: int}[]>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PluginPostActivateEvent::class => [['saveConfiguration', 100]],
            PluginPostUpdateEvent::class => [['saveConfiguration', 100]],
        ];
    }

    public function saveConfiguration(PluginPostActivateEvent|PluginPostUpdateEvent $event): void
    {
        $bundleClassesThatUsesConfigurationCardProviders = $this->configurationCardProviderProvider->getBundleClasses();

        $pluginBaseClass = $event->getPlugin()
            ->getBaseClass();
        if (in_array($pluginBaseClass, $bundleClassesThatUsesConfigurationCardProviders) === false) {
            return;
        }

        $plugin = $this->kernelPluginCollection->get($pluginBaseClass);
        if (! $plugin instanceof Bundle) {
            return;
        }

        $this->systemConfigService->savePluginConfiguration($plugin);
    }
}
