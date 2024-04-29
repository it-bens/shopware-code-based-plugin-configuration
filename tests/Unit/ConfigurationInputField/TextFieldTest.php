<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit\ConfigurationInputField;

use Generator;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\TextField;
use ITB\ShopwareCodeBasedPluginConfiguration\GeneralFieldInformation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TextFieldTest extends TestCase
{
    public static function constructionProvider(): Generator
    {
        $generalInformation = new GeneralFieldInformation('field_1', 'Field 1', 'Feld 1', null, null);
        yield 'default value is "I don\'t know"' => [$generalInformation, "I don't know"];
        yield 'default value is null' => [$generalInformation, null];
    }

    public static function getDefinitionProvider(): Generator
    {
        $generalInformation = new GeneralFieldInformation('field_1', 'Field 1', 'Feld 1', null, null);
        $field = new TextField($generalInformation, "I don't know");
        yield 'default value is "I don\'t know"' => [
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
                'type' => 'text',
                'defaultValue' => "I don't know",
            ],
        ];

        $field = new TextField($generalInformation, null);
        yield 'default value is null' => [
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
                'type' => 'text',
                'defaultValue' => null,
            ],
        ];
    }

    #[DataProvider('constructionProvider')]
    public function testConstruction(GeneralFieldInformation $generalInformation, ?string $defaultValue): void
    {
        $field = new TextField($generalInformation, $defaultValue);
        $this->assertInstanceOf(TextField::class, $field);
    }

    #[DataProvider('getDefinitionProvider')]
    public function testGetDefinition(TextField $field, array $expectedData): void
    {
        $this->assertSame($expectedData, $field->getDefinition());
    }
}
