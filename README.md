# Shopware Code-Based Plugin Configuration

[![Tests](https://github.com/it-bens/shopware-code-based-plugin-configuration/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/it-bens/shopware-code-based-plugin-configuration/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/it-bens/shopware-code-based-plugin-configuration/branch/main/graph/badge.svg?token=jWZCkC3PTl)](https://codecov.io/gh/it-bens/shopware-code-based-plugin-configuration)

Shopware provides an easy way to create a plugin configuration without any implementing any frontend code: https://developer.shopware.com/docs/guides/plugins/plugins/plugin-fundamentals/add-plugin-configuration.html

While the underlying system for the internal configuration generation is very flexible, the `config.xml` file is the only way to leverage this system. This package aims to provide another method: code-based configuration generation.

## Is this a Shopware Plugin?

No. While this package is tied to Shopware, it won't react on any events on it's own. It is meant to be used by a Shopware plugin.

## How can add this package in a Shopware plugin?

First, the package has to be installed via composer:

```bash
composer require it-bens/shopware-code-based-plugin-configuration
```

This can be done in the plugin's `composer.json` file. But remember to enable composer in the plugin class.

```php
public function executeComposerCommands(): bool
{
    return true;
}
```

This package provides a Symfony compiler pass that will add all the services to the Shopware plugin service container. It can be added to the plugin's `build` method.

```php
use ITB\ShopwareCodeBasedPluginConfiguration\DependencyInjection\ConfigurationCardConfigReaderPass;

public function build(ContainerBuilder $container): void
{
    // ...
    parent::build($container);

    $container->addCompilerPass(new BoolToYesNoCompilerPass());
}
```

## How can I use this package?

### Coding the configuration into cards

This package provides classes to define configuration cards (as seen in the Shopware plugin administration).

A `ConfigurationCard` has a title in english, a title in german and a list of `ConfigurationInputField` objects. The supported `ConfigurationInputField` implementations are:
* `BoolField`
* `IntegerField`
* `SingleSelectField`
* `TextField`

Some of these implementations differ in their required information. But because they mostly require the same information, all require a `GeneralFieldInputInformation` object. Here is an example for a configuration card with all types of input fields:

```php
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCard;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\BoolField;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\IntegerField;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\SingleSelectField;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\SingleSelectFieldOption;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\TextField;
use ITB\ShopwareCodeBasedPluginConfiguration\GeneralFieldInputInformation;

$enabledFieldInformation = new GeneralFieldInputInformation(
    name: 'enabled', 
    labelInEnglish: 'Enabled', 
    labelInGerman: 'Aktiviert', 
    helpTextInEnglish: 'Enable or disable the feature', 
    helpTextInGerman: 'Aktiviere oder deaktiviere das Feature'
);
$enabledField = new BoolField(new GeneralFieldInformation(generalInformation: $enabledFieldInformation, defaultValue: false);

$integerFieldInformation = new GeneralFieldInputInformation(
    name: 'position', 
    labelInEnglish: 'Position', 
    labelInGerman: 'Position', 
    helpTextInEnglish: 'The position in the list', 
    helpTextInGerman: 'Die Position in der Liste'
);
$integerField = new IntegerField(new GeneralFieldInformation(generalInformation: $integerFieldInformation, defaultValue: 0);

$selectFieldInformation = new GeneralFieldInputInformation(
    name: 'color', 
    labelInEnglish: 'Color', 
    labelInGerman: 'Farbe', 
    helpTextInEnglish: 'The color of the item', 
    helpTextInGerman: 'Die Farbe des Elements'
);
$selectField = new SingleSelectField(
    new GeneralFieldInformation(generalInformation: $selectFieldInformation, defaultValue: 'red'),
    [
        new SingleSelectFieldOption(id: 'red', nameInEnglish: 'Red', nameInGerman: 'Rot'),
        new SingleSelectFieldOption(id: 'blue', nameInEnglish: 'Blue', nameInGerman: 'Blau'),
        new SingleSelectFieldOption(id: 'green', nameInEnglish: 'Green', nameInGerman: 'Grün'),
    ]
);

$textFieldInformation = new GeneralFieldInputInformation(
    name: 'description', 
    labelInEnglish: 'Description', 
    labelInGerman: 'Beschreibung', 
    helpTextInEnglish: 'A description of the item', 
    helpTextInGerman: 'Eine Beschreibung des Elements'
);
$textField = new TextField(new GeneralFieldInformation(generalInformation: $textFieldInformation, defaultValue: '');

$configurationCard = new ConfigurationCard(
    titleInEnglish: 'General Settings',
    titleInGerman: 'Allgemeine Einstellungen',
    inputFields: [$enabledField, $integerField, $selectField, $textField]
);
```

### Injecting the cards into the configuration

The package uses services that implement the `ConfigurationCardProvider` interface. You can create as many implementations in service instances as you like. Because Shopware uses the same loading mechanism for all plugins, the scope of the `ConfigurationCardProvider` has to be declared. This is done in the `getBundleClasses` method of the plugin class.

```php
/**
 * @return class-string<Bundle>[]
 */
public function getBundleClasses(): array {
    return [
        YourPluginClass::class
    ];
}
```

The `getPriority` method is used to sort the providers if more than one provider is registered for the same plugin.

```php
public function getPriority(): int {
    return 100;
}
```

And finally the `getConfigurationCards` method is used to return the configuration cards.

```php
/**
 * @return ConfigurationCard[]
 */
public function getConfigurationCards(): array {
    return $this->configurationCards;
}
```

## How does the package work?

The package decorates the `Shopware\Core\System\SystemConfig\Util\ConfigReader` service from Shopware. This service is used to read the `config.xml` file of a plugin. It decodes the XML file into an array. This package keeps the XML-based configuration of plugin and extends it with the data from matching configuration card providers.

The decoration is done via compiler pass in three steps:
1. all services that implement the `ConfigurationCardProvider` interface are collected and tagged.
2. a `ConfigurationCardProviderProvider` definition is created that receives all services from (1).
3. the `ConfigurationCardConfigReader` definition is created with decoration of the `Shopware\Core\System\SystemConfig\Util\ConfigReader` service and the `ConfigurationCardProviderProvider` is injected into the `ConfigurationCardConfigReader`.
4. the `ConfigurationCardConfigReader` is set as an alias for the `Shopware\Core\System\SystemConfig\Util\ConfigReader` service to replace it.

## Contributing
I am really happy that the software developer community loves Open Source, like I do! ♥

That's why I appreciate every issue that is opened (preferably constructive) and every pull request that provides other or even better code to this package.

You are all breathtaking!