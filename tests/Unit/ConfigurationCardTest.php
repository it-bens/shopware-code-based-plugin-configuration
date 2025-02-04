<?php

declare(strict_types=1);

namespace ITB\ShopwareCodeBasedPluginConfiguration\Test\Unit;

use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationCard;
use ITB\ShopwareCodeBasedPluginConfiguration\ConfigurationInputField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigurationCard::class)]
final class ConfigurationCardTest extends TestCase
{
    public static function constructionProvider(): \Generator
    {
        yield 'default values' => ['Title in English', 'Titel auf Deutsch', []];
    }

    public static function getDefinitionProvider(): \Generator
    {
        $configurationCard = new ConfigurationCard('Title in English', 'Titel auf Deutsch', []);
        yield [
            $configurationCard,
            [
                'title' => [
                    'en-GB' => 'Title in English',
                    'de-DE' => 'Titel auf Deutsch',
                ],
                'name' => null,
                'elements' => [],
            ],
        ];
    }

    /**
     * @param ConfigurationInputField[] $inputFields
     */
    #[DataProvider('constructionProvider')]
    public function testConstruction(string $titleInEnglish, string $titleInGerman, array $inputFields): void
    {
        $card = new ConfigurationCard($titleInEnglish, $titleInGerman, $inputFields);
        $this->assertInstanceOf(ConfigurationCard::class, $card);
    }

    /**
     * @phpstan-ignore-next-line
     */
    #[DataProvider('getDefinitionProvider')]
    public function testGetDefinition(ConfigurationCard $card, array $expectedData): void
    {
        $this->assertSame($expectedData, $card->getDefinition());
    }
}
