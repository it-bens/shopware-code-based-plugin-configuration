<?php

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\E2E\Plugin;

use ITB\ShopwareCodeBasedPluginConfiguration\Attribute\AsConfigurationCardProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCard;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCardProvider;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\BoolField;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\IntegerField;
use ITB\ShopwareCodeBasedPluginConfiguration\GeneralFieldInformation;

#[AsConfigurationCardProvider]
final readonly class TestConfigurationCardProvider implements ConfigurationCardProvider
{
    public function getBundleClasses(): array
    {
        return [TestPlugin::class];
    }

    public function getConfigurationCards(): array
    {
        $listingFilterProperties = [
            'customerSpecificPrice',
            'specialProductProperty',
            'businessSpecificProductGroup'
        ];

        $configurationCards = [];
        foreach ($listingFilterProperties as $listingFilterProperty) {
            $configurationCards[] = new ConfigurationCard(
                'Product Filter: ' . $listingFilterProperty,
                'ProductFilter: ' . $listingFilterProperty,
                [
                    new BoolField(
                        new GeneralFieldInformation(
                            $listingFilterProperty . 'FilterEnabled',
                            'Filter enabled',
                            'Filter aktiviert',
                            null,
                            null,
                        ),
                        false
                    ),
                    new IntegerField(
                        new GeneralFieldInformation(
                            $listingFilterProperty . 'FilterPosition',
                            'Filter position',
                            'Filter Position',
                            null,
                            null,
                        ),
                        null
                    ),
                ]
            );
        }

        return $configurationCards;
    }

    public static function getExpectedBundleConfig(): array
    {
        return [
            [
                "title" => [
                    "en-GB" => "Product Filter: customerSpecificPrice",
                    "de-DE" => "ProductFilter: customerSpecificPrice"
                ],
                "name" => null,
                "elements" => [
                    [
                        "name" => "customerSpecificPriceFilterEnabled",
                        "label" => [
                            "en-GB" => "Filter enabled",
                            "de-DE" => "Filter aktiviert"
                        ],
                        "helpText" => [
                            "en-GB" => null,
                            "de-DE" => null
                        ],
                        "type" => "bool",
                        "defaultValue" => "false"
                    ],
                    [
                        "name" => "customerSpecificPriceFilterPosition",
                        "label" => [
                            "en-GB" => "Filter position",
                            "de-DE" => "Filter Position"
                        ],
                        "helpText" => [
                            "en-GB" => null,
                            "de-DE" => null
                        ],
                        "type" => "int",
                        "defaultValue" => ""
                    ]
                ]
            ],
            [
                "title" => [
                    "en-GB" => "Product Filter: specialProductProperty",
                    "de-DE" => "ProductFilter: specialProductProperty"
                ],
                "name" => null,
                "elements" => [
                    [
                        "name" => "specialProductPropertyFilterEnabled",
                        "label" => [
                            "en-GB" => "Filter enabled",
                            "de-DE" => "Filter aktiviert"
                        ],
                        "helpText" => [
                            "en-GB" => null,
                            "de-DE" => null
                        ],
                        "type" => "bool",
                        "defaultValue" => "false"
                    ],
                    [
                        "name" => "specialProductPropertyFilterPosition",
                        "label" => [
                            "en-GB" => "Filter position",
                            "de-DE" => "Filter Position"
                        ],
                        "helpText" => [
                            "en-GB" => null,
                            "de-DE" => null
                        ],
                        "type" => "int",
                        "defaultValue" => ""
                    ]
                ]
            ],
            [
                "title" => [
                    "en-GB" => "Product Filter: businessSpecificProductGroup",
                    "de-DE" => "ProductFilter: businessSpecificProductGroup"
                ],
                "name" => null,
                "elements" => [
                    [
                        "name" => "businessSpecificProductGroupFilterEnabled",
                        "label" => [
                            "en-GB" => "Filter enabled",
                            "de-DE" => "Filter aktiviert"
                        ],
                        "helpText" => [
                            "en-GB" => null,
                            "de-DE" => null
                        ],
                        "type" => "bool",
                        "defaultValue" => "false"
                    ],
                    [
                        "name" => "businessSpecificProductGroupFilterPosition",
                        "label" => [
                            "en-GB" => "Filter position",
                            "de-DE" => "Filter Position"
                        ],
                        "helpText" => [
                            "en-GB" => null,
                            "de-DE" => null
                        ],
                        "type" => "int",
                        "defaultValue" => ""
                    ]
                ]
            ]
        ];
    }
}