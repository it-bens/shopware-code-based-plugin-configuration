<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit\ConfigurationInputField;

use Generator;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\BoolField;
use ITB\ShopwareCodeBasedPluginConfiguration\GeneralFieldInformation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class BoolFieldTest extends TestCase
{
    public static function constructionProvider(): Generator
    {
        $generalInformation = new GeneralFieldInformation('field_1', 'Field 1', 'Feld 1', null, null);
        yield 'default value is true' => [$generalInformation, true];
        yield 'default value is false' => [$generalInformation, false];
    }

    public static function getDefinitionProvider(): Generator
    {
        $generalInformation = new GeneralFieldInformation('field_1', 'Field 1', 'Feld 1', null, null);
        $boolField = new BoolField($generalInformation, true);
        yield 'default value is true' => [
            $boolField,
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
                'type' => 'bool',
                'defaultValue' => 'true',
            ],
        ];

        $boolField = new BoolField($generalInformation, false);
        yield 'default value is false' => [
            $boolField,
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
                'type' => 'bool',
                'defaultValue' => 'false',
            ],
        ];
    }

    #[DataProvider('constructionProvider')]
    public function testConstruction(GeneralFieldInformation $generalInformation, bool $defaultValue): void
    {
        $field = new BoolField($generalInformation, $defaultValue);
        $this->assertInstanceOf(BoolField::class, $field);
    }

    /**
     * @phpstan-ignore-next-line
     */
    #[DataProvider('getDefinitionProvider')]
    public function testGetDefinition(BoolField $field, array $expectedData): void
    {
        $this->assertSame($expectedData, $field->getDefinition());
    }
}
