<?php

namespace ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;
use ITB\ShopwareCodeBasedPluginConfiguration\GeneralFieldInformation;

final class SingleSelectField implements ConfigurationInputField
{
    /**
     * @param SingleSelectFieldOption[] $options
     */
    public function __construct(
        private readonly GeneralFieldInformation $generalInformation,
        private readonly ?string $defaultValue,
        private readonly array $options
    ) {
    }

    public function getDefinition(): array
    {
        $definition = $this->generalInformation->getDefinition();

        return array_merge($definition, [
            'type' => 'single-select',
            'defaultValue' => $this->defaultValue,
            'options' => array_values(
                array_map(static fn (SingleSelectFieldOption $option): array => $option->getDefinition(), $this->options)
            ),
        ]);
    }
}
