<?php

declare(strict_types=1);

namespace Tests\Domain\Shared\Normalizer;

use App\Domain\Shared\Normalizer\SymbolNormalizer;
use PHPUnit\Framework\TestCase;

class SymbolNormalizerTest extends TestCase
{
    private readonly SymbolNormalizer $normalizer;

    /** @return array<int, string[]> */
    public static function provideLines(): array
    {
        return [
            ['', ''],
            ['ивaн', 'иван'],
            ['aлeкcaндp', 'александр'],
        ];
    }

    protected function setUp(): void
    {
        $this->normalizer = new SymbolNormalizer();
    }

    /**
     * @test
     * @dataProvider provideLines
     */
    public function it_fixes_en_symbols(string $line, string $normalizedLine): void
    {
        $this->assertEquals($normalizedLine, $this->normalizer->normalize($line));
    }
}
