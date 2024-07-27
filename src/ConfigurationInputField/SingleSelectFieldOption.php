<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;

/**
 * @phpstan-type SingleSelectFieldOptionDefinition array{
 *     id: string,
 *     name: array{'en-GB': string, 'de-DE': string}
 * }
 */
final class SingleSelectFieldOption
{
    public function __construct(
        private readonly string $id,
        private readonly string $nameInEnglish,
        private readonly string $nameInGerman,
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
