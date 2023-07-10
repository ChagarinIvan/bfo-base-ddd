<?php

declare(strict_types=1);

namespace Tests\Application\Service\Cup;

use App\Application\Dto\Cup\CupAssembler;
use App\Application\Dto\Cup\ViewCupDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Cup\Exception\CupNotFound;
use App\Application\Service\Cup\ViewCup;
use App\Application\Service\Cup\ViewCupService;
use App\Domain\Cup\CupId;
use App\Domain\Cup\CupRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Cup\CupFaker;

class ViewCupServiceTest extends TestCase
{
    private ViewCupService $service;

    private CupRepository&MockObject $cups;

    protected function setUp(): void
    {
        $this->service = new ViewCupService(
            $this->cups = $this->createMock(CupRepository::class),
            new CupAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_fails_when_cup_not_found(): void
    {
        $this->expectException(CupNotFound::class);

        $cupId = CupId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->cups
            ->expects($this->once())
            ->method('byId')
            ->with($this->equalTo($cupId))
            ->willReturn(null)
        ;

        $command = new ViewCup('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $this->service->execute($command);
    }

    /** @test */
    public function it_shows_cup(): void
    {
        $cupId = CupId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $cup = CupFaker::fakeCup(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->cups
            ->expects($this->once())
            ->method('byId')
            ->with($this->equalTo($cupId))
            ->willReturn($cup)
        ;

        $command = new ViewCup('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $result = $this->service->execute($command);

        $this->assertInstanceOf(ViewCupDto::class, $result);
        $this->assertEquals('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $result->id);
    }
}
