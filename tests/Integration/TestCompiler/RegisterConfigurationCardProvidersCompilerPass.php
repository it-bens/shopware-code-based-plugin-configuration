<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Integration\TestCompiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterConfigurationCardProvidersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
    }
}
