<?php

namespace ITB\ShopwareCodeBasedPluginConfiguration;

final class ConfigurationCard
{
    /**
     * @param ConfigurationInputField[] $inputFields
     */
    public function __construct(
        private readonly string $titleInEnglish,
        private readonly string $titleInGerman,
        private readonly array $inputFields,
    ) {
    }

    public function getDefinition(): array
    {
        return [
            'title' => [
                'en-GB' => $this->titleInEnglish,
                'de-DE' => $this->titleInGerman,
            ],
            'name' => null,
            'elements' => array_values(array_map(static fn ($inputField): array => $inputField->getDefinition(), $this->inputFields)),
        ];
    }
}
