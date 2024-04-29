<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit\ConfigurationInputField;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\SingleSelectField;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\SingleSelectFieldOption;
use ITB\ShopwareCodeBasedPluginConfiguration\GeneralFieldInformation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SingleSelectFieldTest extends TestCase
{
    public static function constructionProvider(): \Generator
    {
        $generalInformation = new GeneralFieldInformation('field_1', 'Field 1', 'Feld 1', null, null);
        $option1 = new SingleSelectFieldOption('option_1', 'Option 1', 'Option 1');
        $option2 = new SingleSelectFieldOption('option_2', 'Option 2', 'Option 2');
        yield 'default value is "option_1"' => [$generalInformation, 'option_1', [$option1, $option2]];
    }

    public static function getDefinitionProvider(): \Generator
    {
        $generalInformation = new GeneralFieldInformation('field_1', 'Field 1', 'Feld 1', null, null);
        $option1 = new SingleSelectFieldOption('option_1', 'Option 1', 'Option 1');
        $option2 = new SingleSelectFieldOption('option_2', 'Option 2', 'Option 2');
        $field = new SingleSelectField($generalInformation, 'option_1', [$option1, $option2]);
        yield 'default value is "option_1"' => [
            $field,
            [
                'name' => 'field_1',
                'label' => [
                    'en-GB' => 'Field 1',
                    'de-DE' => 'Feld 1',
                ],
                'helpText' => [
                    'en-GB' => null,
                    'de-DE' => null,
                ],
                'type' => 'single-select',
                'defaultValue' => 'option_1',
                'options' => [
                    [
                        'id' => 'option_1',
                        'name' => [
                            'en-GB' => 'Option 1',
                            'de-DE' => 'Option 1',
                        ],
                    ],
                    [
                        'id' => 'option_2',
                        'name' => [
                            'en-GB' => 'Option 2',
                            'de-DE' => 'Option 2',
                        ],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('constructionProvider')]
    public function testConstruction(GeneralFieldInformation $generalInformation, string $defaultValue, array $options): void
    {
        $field = new SingleSelectField($generalInformation, $defaultValue, $options);
        $this->assertInstanceOf(SingleSelectField::class, $field);
    }

    #[DataProvider('getDefinitionProvider')]
    public function testGetDefinition(SingleSelectField $field, array $expectedData): void
    {
        $this->assertSame($expectedData, $field->getDefinition());
    }
}
