<?php

declare(strict_types=1);

namespace Tests\Application\Service\Person;

use App\Application\Dto\Person\PersonAssembler;
use App\Application\Dto\Person\PersonDto;
use App\Application\Dto\Person\PersonInfoDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Person\AddPerson;
use App\Application\Service\Person\AddPersonService;
use App\Domain\Club\ClubId;
use App\Domain\Person\Factory\PersonFactory;
use App\Domain\Person\Factory\PersonInput;
use App\Domain\Person\PersonInfo;
use App\Domain\Person\PersonRepository;
use App\Domain\Shared\Metadata;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Person\PersonFaker;
use Tests\Faker\Shared\AuthFaker;

class AddPersonServiceTest extends TestCase
{
    private AddPersonService $service;

    private PersonFactory&MockObject $factory;

    private PersonRepository&MockObject $persons;

    protected function setUp(): void
    {
        $this->service = new AddPersonService(
            $this->factory = $this->createMock(PersonFactory::class),
            $this->persons = $this->createMock(PersonRepository::class),
            new PersonAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_creates_person(): void
    {
        $info = new PersonInfo(
            'test firstname',
            'test lastname',
            1988,
        );

        $input = new PersonInput(
            $info,
            Metadata::fromArray(['orient_by_id' => '123']),
            AuthFaker::fakeFootprint(),
            ClubId::fromString('ab79834f-494b-4137-9280-eb496328addf'),
        );
        $person = PersonFaker::fakePerson();

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo($input))
            ->willReturn($person)
        ;

        $this->persons
            ->expects($this->once())
            ->method('add')
            ->with($this->identicalTo($person))
        ;

        $infoDto = new PersonInfoDto();
        $infoDto->firstname = 'test firstname';
        $infoDto->lastname = 'test lastname';
        $infoDto->yearOfBirthday = '1988';

        $dto = new PersonDto();
        $dto->info = $infoDto;
        $dto->attributes = ['orient_by_id' => '123'];
        $dto->clubId = 'ab79834f-494b-4137-9280-eb496328addf';

        $command = new AddPerson($dto, AuthFaker::fakeFootprintDto());
        $personDto = $this->service->execute($command);

        $this->assertEquals($person->id()->toString(), $personDto->id);
    }
}
