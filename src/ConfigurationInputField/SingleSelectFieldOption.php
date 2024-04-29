<?php

namespace ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;

final class SingleSelectFieldOption
{
    public function __construct(
        private readonly string $id,
        private readonly string $nameInEnglish,
        private readonly string $nameInGerman,
    ) {
    }

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
