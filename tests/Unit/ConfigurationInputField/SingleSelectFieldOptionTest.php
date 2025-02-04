<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit\ConfigurationInputField;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField\SingleSelectFieldOption;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(SingleSelectFieldOption::class)]
final class SingleSelectFieldOptionTest extends TestCase
{
    public static function constructionProvider(): \Generator
    {
        yield 'default values' => ['option_1', 'Option 1', 'Option 1'];
    }

    public static function getDefinitionProvider(): \Generator
    {
        $singleSelectFieldOption = new SingleSelectFieldOption('option_1', 'Option 1', 'Option 1');
        yield [
            $singleSelectFieldOption,
            [
                'id' => 'option_1',
                'name' => [
                    'en-GB' => 'Option 1',
                    'de-DE' => 'Option 1',
                ],
            ],
        ];
    }

    #[DataProvider('constructionProvider')]
    public function testConstruction(string $id, string $nameInEnglish, string $nameInGerman): void
    {
        $singleSelectFieldOption = new SingleSelectFieldOption($id, $nameInEnglish, $nameInGerman);
        $this->assertInstanceOf(SingleSelectFieldOption::class, $singleSelectFieldOption);
    }

    /**
     * @phpstan-ignore-next-line
     */
    #[DataProvider('getDefinitionProvider')]
    public function testGetDefinition(SingleSelectFieldOption $singleSelectFieldOption, array $expectedData): void
    {
        $this->assertSame($expectedData, $singleSelectFieldOption->getDefinition());
    }
}
