<?php

declare(strict_types=1);

namespace Tests\Domain\Shared\Normalizer;

use App\Domain\Shared\Normalizer\CaseNormalizer;
use PHPUnit\Framework\TestCase;

class CaseNormalizerTest extends TestCase
{
    private readonly CaseNormalizer $normalizer;

    /** @return array<int, string[]> */
    public static function provideLines(): array
    {
        return [
            ['', ''],
            ['TEST', 'test'],
            ['ТЕСТ', 'тест'],
            ['Тес т', 'тес т'],
            ['!"№;%:?"\'', '!"№;%:?"\''],
        ];
    }

    protected function setUp(): void
    {
        $this->normalizer = new CaseNormalizer();
    }

    /**
     * @test
     * @dataProvider provideLines
     */
    public function it_lowerizes_text_case(string $line, string $normalizedLine): void
    {
        $this->assertEquals($normalizedLine, $this->normalizer->normalize($line));
    }
}
