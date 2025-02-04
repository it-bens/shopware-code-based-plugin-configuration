<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;

/**
 * @phpstan-type SingleSelectFieldOptionDefinition array{
 *     id: string,
 *     name: array{'en-GB': string, 'de-DE': string}
 * }
 */
final readonly class SingleSelectFieldOption
{
    public function __construct(
        private string $id,
        private string $nameInEnglish,
        private string $nameInGerman,
    ) {
    }

    /**
     * @return SingleSelectFieldOptionDefinition
     */
    public function getDefinition(): array
    {
        return [
            'id' => $this->id,
            'name' => [
                'en-GB' => $this->nameInEnglish,
                'de-DE' => $this->nameInGerman,
            ],
        ];
    }
}
