<?php

declare(strict_types=1);

namespace Tests\Application\Service\Person;

use App\Application\Service\Person\ChangePersonClub;
use App\Application\Service\Person\ChangePersonClubService;
use App\Application\Service\Person\Exception\PersonNotFound;
use App\Domain\Person\PersonId;
use App\Domain\Person\PersonRepository;
use App\Domain\Shared\DummyTransactional;
use App\Domain\Shared\FrozenClock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Person\PersonFaker;
use Tests\Faker\Shared\AuthFaker;

class ChangePersonClubServiceTest extends TestCase
{
    private ChangePersonClubService $service;

    private PersonRepository&MockObject $persons;

    protected function setUp(): void
    {
        $this->service = new ChangePersonClubService(
            $this->persons = $this->createMock(PersonRepository::class),
            new FrozenClock(),
            new DummyTransactional(),
        );
    }

    /** @test */
    public function it_fails_when_person_not_found(): void
    {
        $this->expectException(PersonNotFound::class);

        $personId = PersonId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');

        $this->persons
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($personId))
            ->willReturn(null)
        ;

        $command = new ChangePersonClub(
            '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b',
            '8b019eed-d63a-4339-a81d-6a84f59a24fd',
            AuthFaker::fakeFootprintDto(),
        );

        $this->service->execute($command);
    }

    /** @test */
    public function it_changes_person_club(): void
    {
        $personId = PersonId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $person = PersonFaker::fakePerson(clubId: '8b019eed-d63a-4339-a81d-6a84f59a24fd');

        $this->persons
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($personId))
            ->willReturn($person)
        ;

        $this->persons
            ->expects($this->once())
            ->method('update')
            ->with($this->identicalTo($person))
        ;

        $command = new ChangePersonClub(
            '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b',
            '6d08156b-789b-42b6-9643-2cec9dce09f2',
            AuthFaker::fakeFootprintDto(),
        );
        $this->service->execute($command);

        $this->assertEquals('6d08156b-789b-42b6-9643-2cec9dce09f2', $person->clubId()->toString());
    }
}
