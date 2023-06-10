<?php

declare(strict_types=1);

namespace Tests\Application\Service\Person;

use App\Application\Service\Person\DeletePerson;
use App\Application\Service\Person\DeletePersonService;
use App\Application\Service\Person\Exception\PersonNotFound;
use App\Domain\Person\PersonId;
use App\Domain\Person\PersonRepository;
use App\Domain\Shared\DummyTransactional;
use App\Domain\Shared\FrozenClock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Person\PersonFaker;
use Tests\Faker\Shared\AuthFaker;

class DeletePersonServiceTest extends TestCase
{
    private DeletePersonService $service;

    private PersonRepository&MockObject $persons;

    protected function setUp(): void
    {
        $this->service = new DeletePersonService(
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

        $command = new DeletePerson('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', AuthFaker::fakeFootprintDto());
        $this->service->execute($command);
    }

    /** @test */
    public function it_deletes_person(): void
    {
        $personId = PersonId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $person = PersonFaker::fakePerson();

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

        $command = new DeletePerson('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b', AuthFaker::fakeFootprintDto());
        $this->service->execute($command);

        $this->assertTrue($person->disabled());
    }
}
