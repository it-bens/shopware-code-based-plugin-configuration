<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Integration;

use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\CompilerPassHelper;
use ITB\ShopwareCodeBasedPluginConfiguration\Test\Integration\TestCompiler\PublishServicesForTestsCompilerPass;
use ITB\ShopwareCodeBasedPluginConfiguration\Test\Integration\TestCompiler\ShopwareServicesCompilerPass;
use Symfony\Component\Config\Loader\LoaderInterface;
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

            CompilerPassHelper::addCompilerPassesToContainerBuilder($container);

            $container->addCompilerPass(new PublishServicesForTestsCompilerPass());
        });
    }
}
