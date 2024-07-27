<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\SingleSelectFieldOption;

/**
 * @phpstan-import-type SingleSelectFieldOptionDefinition from SingleSelectFieldOption
 * @phpstan-type ConfigurationInputFieldDefinition array{
 *     name: string,
 *     label: array{'en-GB': string, 'de-DE': string},
 *     helpText: array{'en-GB': string|null, 'de-DE': string|null},
 *     type: string,
 *     defaultValue: string|null,
 *     options?: SingleSelectFieldOptionDefinition[],
 * }
 */
interface ConfigurationInputField
{
    /**
     * @return ConfigurationInputFieldDefinition
     */
    public function getDefinition(): array;
}
