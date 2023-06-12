<?php

declare(strict_types=1);

namespace Tests\Domain\Shared\Normalizer;

use App\Domain\Shared\Normalizer\FirstnameNormalizer;
use PHPUnit\Framework\TestCase;

class FirstnameNormalizerTest extends TestCase
{
    private readonly FirstnameNormalizer $normalizer;

    /** @return array<int, string[]> */
    public static function provideLines(): array
    {
        return [
            ['', ''],
            ['миша', 'михаил'],
            ['мишаня', 'мишаня'],
            ['ваня', 'иван'],
        ];
    }

    protected function setUp(): void
    {
        $this->normalizer = new FirstnameNormalizer();
    }

    /**
     * @test
     * @dataProvider provideLines
     */
    public function it_fixes_names(string $line, string $normalizedLine): void
    {
        $this->assertEquals($normalizedLine, $this->normalizer->normalize($line));
    }
}
