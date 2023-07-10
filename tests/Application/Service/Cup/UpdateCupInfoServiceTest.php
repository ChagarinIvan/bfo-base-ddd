<?php

declare(strict_types=1);

namespace Tests\Application\Service\Cup;

use App\Application\Dto\Cup\CupAssembler;
use App\Application\Dto\Cup\CupDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Cup\Exception\CupNotFound;
use App\Application\Service\Cup\UpdateCup;
use App\Application\Service\Cup\UpdateCupInfoService;
use App\Domain\Cup\CupId;
use App\Domain\Cup\CupRepository;
use App\Domain\Shared\DummyTransactional;
use App\Domain\Shared\FrozenClock;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Cup\CupFaker;
use Tests\Faker\Shared\AuthFaker;

class UpdateCupInfoServiceTest extends TestCase
{
    private UpdateCupInfoService $service;

    private CupRepository&MockObject $cups;

    protected function setUp(): void
    {
        $this->service = new UpdateCupInfoService(
            $this->cups = $this->createMock(CupRepository::class),
            new FrozenClock(new DateTimeImmutable('2023-04-01')),
            new CupAssembler(new AuthAssembler()),
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

        $info = new CupDto();
        $info->name = 'test cup';
        $info->eventCounts = 3;
        $info->year = 2023;
        $info->type = 'master';

        $command = new UpdateCup('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $info, AuthFaker::fakeFootprintDto());
        $this->service->execute($command);
    }

    /** @test */
    public function it_updates_cup_info(): void
    {
        $cupId = CupId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $event = CupFaker::fakeCup(id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->cups
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($cupId))
            ->willReturn($event)
        ;

        $this->cups->expects($this->once())->method('update');

        $info = new CupDto();
        $info->name = 'test cup';
        $info->eventCounts = 4;
        $info->year = 2022;
        $info->type = 'master';

        $command = new UpdateCup('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', $info, AuthFaker::fakeFootprintDto());
        $event = $this->service->execute($command);

        $this->assertEquals('test cup', $event->name);
        $this->assertEquals(4, $event->eventsCount);
        $this->assertEquals(2022, $event->year);
        $this->assertEquals('master', $event->type);
        $this->assertEquals('2023-04-01 00:00:00', $event->updated->at);
    }
}
