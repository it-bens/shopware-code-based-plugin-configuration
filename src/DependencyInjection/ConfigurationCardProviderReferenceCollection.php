<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection;

use Symfony\Component\DependencyInjection\Reference;

final class ConfigurationCardProviderReferenceCollection
{
    /**
     * @var array{id: string, priority: int}[]
     */
    private array $configurationCardProviderIdsWithPriorities = [];

    public function add(string $id, int $priority): void
    {
        $this->configurationCardProviderIdsWithPriorities[] = [
            'id' => $id,
            'priority' => $priority,
        ];
    }

    /**
     * @return Reference[]
     */
    public function getReferences(): array
    {
        uasort(
            $this->configurationCardProviderIdsWithPriorities,
            static fn (array $a, array $b): int => $b['priority'] <=> $a['priority']
        );

        return array_values(array_map(
            static fn (array $configurationCardProviderIdWithPriority): Reference => new Reference(
                $configurationCardProviderIdWithPriority['id']
            ),
            $this->configurationCardProviderIdsWithPriorities
        ));
    }
}
