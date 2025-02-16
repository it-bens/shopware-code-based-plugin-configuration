<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfigurationTestPlugin;

use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\CompilerPassHelper;
use Shopware\Core\Framework\Plugin;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class TestPlugin extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/DependencyInjection'));
        $loader->load('services.php');

        CompilerPassHelper::addCompilerPassesToContainerBuilder($container);
    }
}
