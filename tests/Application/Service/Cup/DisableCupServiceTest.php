<?php

declare(strict_types=1);

namespace Tests\Application\Service\Cup;

use App\Application\Service\Cup\DisableCup;
use App\Application\Service\Cup\DisableCupService;
use App\Application\Service\Cup\Exception\CupNotFound;
use App\Domain\Cup\CupId;
use App\Domain\Cup\CupRepository;
use App\Domain\Shared\DummyTransactional;
use App\Domain\Shared\FrozenClock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Cup\CupFaker;
use Tests\Faker\Shared\AuthFaker;

class DisableCupServiceTest extends TestCase
{
    private DisableCupService $service;

    private CupRepository&MockObject $cups;

    protected function setUp(): void
    {
        $this->service = new DisableCupService(
            $this->cups = $this->createMock(CupRepository::class),
            new FrozenClock(),
            new DummyTransactional(),
        );
    }

    /** @test */
    public function it_fails_when_cup_not_found(): void
    {
        $this->expectException(CupNotFound::class);

        $cupId = CupId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->cups
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($cupId))
            ->willReturn(null)
        ;

        $command = new DisableCup('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', AuthFaker::fakeFootprintDto());
        $this->service->execute($command);
    }

    /** @test */
    public function it_disables_cup(): void
    {
        $cupId = CupId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $cup = CupFaker::fakeCup();

        $this->cups
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($cupId))
            ->willReturn($cup)
        ;

        $this->cups
            ->expects($this->once())
            ->method('update')
            ->with($this->identicalTo($cup))
        ;

        $command = new DisableCup('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', AuthFaker::fakeFootprintDto());
        $this->service->execute($command);

        $this->assertTrue($cup->disabled());
    }
}
