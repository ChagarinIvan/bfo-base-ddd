<?php

declare(strict_types=1);

namespace Tests\Domain\Shared\Normalizer;

use App\Domain\Shared\Normalizer\ChainNormalizer;
use App\Domain\Shared\Normalizer\Normalizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ChainNormalizerTest extends TestCase
{
    private readonly ChainNormalizer $normalizer;
    private Normalizer&MockObject $firstNormalizer;

    private Normalizer&MockObject $secondNormalizer;

    protected function setUp(): void
    {
        $this->firstNormalizer = $this->createMock(Normalizer::class);
        $this->secondNormalizer = $this->createMock(Normalizer::class);

        $this->normalizer = new ChainNormalizer([
            $this->firstNormalizer,
            $this->secondNormalizer,
        ]);
    }

    /** @test */
    public function it_delegates_normalization(): void
    {
        $this->firstNormalizer
            ->expects($this->once())
            ->method('normalize')
            ->with('test')
            ->willReturn('test1')
        ;

        $this->secondNormalizer
            ->expects($this->once())
            ->method('normalize')
            ->with('test1')
            ->willReturn('test2')
        ;

        $this->assertEquals('test2', $this->normalizer->normalize('test'));
    }
}
