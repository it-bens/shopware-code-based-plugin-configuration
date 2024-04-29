<?php

namespace ITB\ShopwareCodeBasedPluginConfiguration;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardConfigReader\ConfigurationCardProviderProviderInterface;
use Shopware\Core\Framework\Bundle;
use Shopware\Core\System\SystemConfig\Util\ConfigReader as BundleXmlConfigReader;

final class ConfigurationCardConfigReader extends BundleXmlConfigReader
{
    public function __construct(
        private readonly BundleXmlConfigReader $bundleXmlConfigReader,
        private readonly ConfigurationCardProviderProviderInterface $configurationCardProviderProvider
    ) {
    }

    public function getConfigFromBundle(Bundle $bundle, ?string $bundleConfigName = null): array
    {
        $config = $this->bundleXmlConfigReader->getConfigFromBundle($bundle, $bundleConfigName);

        foreach ($this->configurationCardProviderProvider->getConfigurationCardProviders() as $configurationCardProvider) {
            $isBundleProviderApplicable = false;
            foreach ($configurationCardProvider->getBundleClasses() as $bundleClass) {
                if ($bundle instanceof $bundleClass) {
                    $isBundleProviderApplicable = true;
                    break;
                }
            }

            if (! $isBundleProviderApplicable) {
                continue;
            }

            $generatedConfiguration = array_values(
                array_map(
                    static fn (ConfigurationCard $configurationCard): array => $configurationCard->getDefinition(),
                    $configurationCardProvider->getConfigurationCards()
                )
            );

            $config = array_merge($config, $generatedConfiguration);
        }

        return $config;
    }
}
