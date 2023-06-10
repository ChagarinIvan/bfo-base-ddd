<?php

declare(strict_types=1);

namespace Tests\Domain\Club\Factory;

use App\Domain\Club\Factory\ClubFactory;
use App\Domain\Club\Factory\ClubInput;
use App\Domain\Club\Factory\NormalizeClubNameClubFactory;
use App\Domain\Shared\Normalizer\Normalizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Club\ClubFaker;
use Tests\Faker\Shared\AuthFaker;

class NormalizeClubNameClubFactoryTest extends TestCase
{
    private readonly ClubFactory&MockObject $decorated;

    private readonly Normalizer&MockObject $normalizer;

    private readonly NormalizeClubNameClubFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new NormalizeClubNameClubFactory(
            $this->decorated = $this->createMock(ClubFactory::class),
            $this->normalizer = $this->createMock(Normalizer::class),
        );
    }

    /** @test */
    public function it_normalizes_club_name(): void
    {
        $footprint = AuthFaker::fakeFootprint();
        $baseInput = new ClubInput('Кaмвoль', $footprint);
        $expectedInput = new ClubInput('Камволь', $footprint);

        $this->decorated
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo($expectedInput))
            ->willReturn(ClubFaker::fakeClub())
        ;

        $this->normalizer
            ->expects($this->once())
            ->method('normalize')
            ->with($this->equalTo('Кaмвoль'))
            ->willReturn('Камволь')
        ;

        $this->factory->create($baseInput);
    }
}
