<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Function;

use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\ConfigurationCardConfigReaderPass;
use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\ConfigurationCardConfigSaverPass;
use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\ConfigurationCardProviderTaggingPass;
use ITB\ShopwareCodeBasedPluginConfiguration\Test\Function\TestCompiler\PublishServicesForTestsCompilerPass;
use ITB\ShopwareCodeBasedPluginConfiguration\Test\Function\TestCompiler\ShopwareServicesCompilerPass;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Kernel;

final class ITBShopwareCodeBasePluginConfigrationTestKernel extends Kernel
{
    /**
     * @param class-string[] $configurationCardProviderClassesToRegister
     */
    public function __construct(
        private readonly array $configurationCardProviderClassesToRegister
    ) {
        parent::__construct('test', true);
    }

    public function getCacheDir(): string
    {
        return __DIR__ . '/../../var/cache/' . spl_object_hash($this);
    }

    public function registerBundles(): iterable
    {
        return [];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container): void {
            foreach ($this->configurationCardProviderClassesToRegister as $configurationCardProviderClassToRegister) {
                $configurationCardProvider = new Definition($configurationCardProviderClassToRegister);
                $configurationCardProvider->setAutowired(true);
                $configurationCardProvider->setAutoconfigured(true);
                $container->setDefinition($configurationCardProviderClassToRegister, $configurationCardProvider);
            }

            $container->addCompilerPass(new ShopwareServicesCompilerPass());

            $container->addCompilerPass(new ConfigurationCardProviderTaggingPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1000);
            $container->addCompilerPass(new ConfigurationCardConfigReaderPass());
            $container->addCompilerPass(new ConfigurationCardConfigSaverPass());

            $container->addCompilerPass(new PublishServicesForTestsCompilerPass());
        });
    }
}
