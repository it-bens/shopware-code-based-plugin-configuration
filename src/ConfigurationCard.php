<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration;

/**
 * @phpstan-import-type ConfigurationInputFieldDefinition from ConfigurationInputField
 * @phpstan-type ConfigurationCardDefinition array{
 *     title: array{'en-GB': string, 'de-DE': string},
 *     name: null,
 *     elements: ConfigurationInputFieldDefinition[]
 * }
 */
final readonly class ConfigurationCard
{
    /**
     * @param ConfigurationInputField[] $inputFields
     */
    public function __construct(
        private string $titleInEnglish,
        private string $titleInGerman,
        private array $inputFields,
    ) {
    }

    /**
     * @return ConfigurationCardDefinition
     */
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
