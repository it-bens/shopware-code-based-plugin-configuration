<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration;

final readonly class GeneralFieldInformation
{
    public function __construct(
        private string $name,
        private string $labelInEnglish,
        private string $labelInGerman,
        private ?string $helpTextInEnglish,
        private ?string $helpTextInGerman,
    ) {
    }

    /**
     * @return array{
     *     name: string,
     *     label: array{'en-GB': string, 'de-DE': string},
     *     helpText: array{'en-GB': string|null, 'de-DE': string|null},
     * }
     */
    public function getDefinition(): array
    {
        return [
            'name' => $this->name,
            'label' => [
                'en-GB' => $this->labelInEnglish,
                'de-DE' => $this->labelInGerman,
            ],
            'helpText' => [
                'en-GB' => $this->helpTextInEnglish,
                'de-DE' => $this->helpTextInGerman,
            ],
        ];
    }
}
