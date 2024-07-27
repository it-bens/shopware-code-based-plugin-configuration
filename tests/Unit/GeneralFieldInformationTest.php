<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit;

use ITB\ShopwareCodeBasedPluginConfiguration\GeneralFieldInformation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class GeneralFieldInformationTest extends TestCase
{
    public static function constructionProvider(): \Generator
    {
        yield 'default values' => ['field_1', 'Field 1', 'Feld 1', 'Help text', 'Hilfetext'];
    }

    public static function getDefinitionProvider(): \Generator
    {
        $generalInformation = new GeneralFieldInformation('field_1', 'Field 1', 'Feld 1', 'Help text', 'Hilfetext');
        yield [
            $generalInformation,
            [
                'name' => 'field_1',
                'label' => [
                    'en-GB' => 'Field 1',
                    'de-DE' => 'Feld 1',
                ],
                'helpText' => [
                    'en-GB' => 'Help text',
                    'de-DE' => 'Hilfetext',
                ],
            ],
        ];
    }

    #[DataProvider('constructionProvider')]
    public function testConstruction(string $name, string $labelEnGb, string $labelDeDe, string $helpTextEnGb, string $helpTextDeDe): void
    {
        $fieldInformation = new GeneralFieldInformation($name, $labelEnGb, $labelDeDe, $helpTextEnGb, $helpTextDeDe);
        $this->assertInstanceOf(GeneralFieldInformation::class, $fieldInformation);
    }

    /**
     * @phpstan-ignore-next-line
     */
    #[DataProvider('getDefinitionProvider')]
    public function testGetDefinition(GeneralFieldInformation $fieldInformation, array $expectedData): void
    {
        $this->assertSame($expectedData, $fieldInformation->getDefinition());
    }
}
