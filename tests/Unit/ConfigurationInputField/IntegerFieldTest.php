<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit\ConfigurationInputField;

use Generator;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\IntegerField;
use ITB\ShopwareCodeBasedPluginConfiguration\GeneralFieldInformation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(IntegerField::class)]
final class IntegerFieldTest extends TestCase
{
    public static function constructionProvider(): Generator
    {
        $generalInformation = new GeneralFieldInformation('field_1', 'Field 1', 'Feld 1', null, null);
        yield 'default value is 5' => [$generalInformation, 5];
        yield 'default value is null' => [$generalInformation, null];
    }

    public static function getDefinitionProvider(): Generator
    {
        $generalInformation = new GeneralFieldInformation('field_1', 'Field 1', 'Feld 1', null, null);
        $field = new IntegerField($generalInformation, 5);
        yield 'default value is 5' => [
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
                'type' => 'int',
                'defaultValue' => '5',
            ],
        ];

        $field = new IntegerField($generalInformation, null);
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
                'type' => 'int',
                'defaultValue' => '',
            ],
        ];
    }

    #[DataProvider('constructionProvider')]
    public function testConstruction(GeneralFieldInformation $generalInformation, ?int $defaultValue): void
    {
        $field = new IntegerField($generalInformation, $defaultValue);
        $this->assertInstanceOf(IntegerField::class, $field);
    }

    /**
     * @phpstan-ignore-next-line
     */
    #[DataProvider('getDefinitionProvider')]
    public function testGetDefinition(IntegerField $field, array $expectedData): void
    {
        $this->assertSame($expectedData, $field->getDefinition());
    }
}
