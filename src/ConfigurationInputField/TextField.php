<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;
use ITB\ShopwareCodeBasedPluginConfiguration\GeneralFieldInformation;

final readonly class TextField implements ConfigurationInputField
{
    public function __construct(
        private GeneralFieldInformation $generalInformation,
        private ?string $defaultValue,
    ) {
    }

    public function getDefinition(): array
    {
        $definition = $this->generalInformation->getDefinition();

        return array_merge($definition, [
            'type' => 'text',
            'defaultValue' => $this->defaultValue,
        ]);
    }
}
