<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit\DependencyInjection;

use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\CompilerPass\ConfigurationCardConfigReaderPass;
use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\CompilerPass\ConfigurationCardConfigSaverPass;
use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\CompilerPass\ConfigurationCardProviderTaggingPass;
use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\CompilerPassHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\AttributeAutoconfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class CompilerPassHelperTest extends TestCase
{
    public function testAddCompilerPassesToContainerBuilder(): void
    {
        $container = new ContainerBuilder();
        CompilerPassHelper::addCompilerPassesToContainerBuilder($container);

        $beforeOptimizationPasses = $container->getCompilerPassConfig()
            ->getBeforeOptimizationPasses();

        $configurationCardProviderTaggingPassIndex = null;
        $attributeAutoconfigurationPassIndex = null;
        $configurationCardConfigReaderPassIndex = null;
        $configurationCardConfigSaverPassIndex = null;

        foreach ($beforeOptimizationPasses as $index => $beforeOptimizationPass) {
            if ($beforeOptimizationPass instanceof ConfigurationCardProviderTaggingPass) {
                $configurationCardProviderTaggingPassIndex = $index;
            }

            if ($beforeOptimizationPass instanceof AttributeAutoconfigurationPass) {
                $attributeAutoconfigurationPassIndex = $index;
            }

            if ($beforeOptimizationPass instanceof ConfigurationCardConfigReaderPass) {
                $configurationCardConfigReaderPassIndex = $index;
            }

            if ($beforeOptimizationPass instanceof ConfigurationCardConfigSaverPass) {
                $configurationCardConfigSaverPassIndex = $index;
            }
        }

        $this->assertNotNull($configurationCardProviderTaggingPassIndex);
        $this->assertNotNull($configurationCardConfigReaderPassIndex);
        $this->assertNotNull($configurationCardConfigSaverPassIndex);
        $this->assertLessThan($attributeAutoconfigurationPassIndex, $configurationCardProviderTaggingPassIndex);
    }
}
