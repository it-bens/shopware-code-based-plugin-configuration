<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;
use ITB\ShopwareCodeBasedPluginConfiguration\GeneralFieldInformation;

final readonly class IntegerField implements ConfigurationInputField
{
    public function __construct(
        private GeneralFieldInformation $generalInformation,
        private ?int $defaultValue,
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
