<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Function\TestCompiler;

use Shopware\Core\Framework\Plugin\KernelPluginCollection;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\System\SystemConfig\Util\ConfigReader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class ShopwareServicesCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $configReader = new Definition(ConfigReader::class);
        $container->setDefinition(ConfigReader::class, $configReader);

        $kernelPluginCollection = new Definition(KernelPluginCollection::class);
        $container->setDefinition(KernelPluginCollection::class, $kernelPluginCollection);

        $systemConfigService = new Definition(SystemConfigService::class);
        $container->setDefinition(SystemConfigService::class, $systemConfigService);
    }
}
