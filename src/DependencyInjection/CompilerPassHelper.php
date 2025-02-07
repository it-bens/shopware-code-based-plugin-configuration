<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection;

use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\CompilerPass\ConfigurationCardConfigReaderPass;
use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\CompilerPass\ConfigurationCardConfigSaverPass;
use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\CompilerPass\ConfigurationCardProviderTaggingPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final readonly class CompilerPassHelper
{
    public static function addCompilerPassesToContainerBuilder(ContainerBuilder $container, int $taggingPassPriority = 1000): void
    {
        $container->addCompilerPass(new ConfigurationCardProviderTaggingPass(), priority: $taggingPassPriority);
        $container->addCompilerPass(new ConfigurationCardConfigReaderPass());
        $container->addCompilerPass(new ConfigurationCardConfigSaverPass());
    }
}
