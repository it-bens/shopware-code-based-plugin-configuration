<?php

namespace ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;
use ITB\ShopwareCodeBasedPluginConfiguration\GeneralFieldInformation;

final class IntegerField implements ConfigurationInputField
{
    public function __construct(
        private readonly GeneralFieldInformation $generalInformation,
        private readonly ?int $defaultValue,
    ) {
    }

    public function getDefinition(): array
    {
        $definition = $this->generalInformation->getDefinition();

        return array_merge($definition, [
            'type' => 'int',
            // The default value must be a string because Shopware uses XmlReader::phpize to process the value.
            // XmlReader::phpize only accepts strings and stringable values.
            'defaultValue' => (string) $this->defaultValue,
        ]);
    }
}
