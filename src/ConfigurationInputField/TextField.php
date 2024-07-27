<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;
use ITB\ShopwareCodeBasedPluginConfiguration\GeneralFieldInformation;

final class TextField implements ConfigurationInputField
{
    public function __construct(
        private readonly GeneralFieldInformation $generalInformation,
        private readonly ?string $defaultValue,
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
