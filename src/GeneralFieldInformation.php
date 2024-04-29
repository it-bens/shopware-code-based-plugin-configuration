<?php

namespace ITB\ShopwareCodeBasedPluginConfiguration;

final class GeneralFieldInformation
{
    public function __construct(
        private readonly string $name,
        private readonly string $labelInEnglish,
        private readonly string $labelInGerman,
        private readonly ?string $helpTextInEnglish,
        private readonly ?string $helpTextInGerman,
    ) {
    }

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
