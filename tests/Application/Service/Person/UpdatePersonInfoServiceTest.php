<?php

declare(strict_types=1);

namespace Tests\Application\Service\Person;

use App\Application\Dto\Person\PersonAssembler;
use App\Application\Dto\Person\PersonInfoDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Person\Exception\PersonNotFound;
use App\Application\Service\Person\UpdatePersonInfo;
use App\Application\Service\Person\UpdatePersonInfoService;
use App\Domain\Person\PersonId;
use App\Domain\Person\PersonInfoNormalizer;
use App\Domain\Person\PersonRepository;
use App\Domain\Shared\DummyTransactional;
use App\Domain\Shared\FrozenClock;
use App\Domain\Shared\Normalizer\Normalizer;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Person\PersonFaker;
use Tests\Faker\Shared\AuthFaker;

class UpdatePersonInfoServiceTest extends TestCase
{
    private UpdatePersonInfoService $service;

    private PersonRepository&MockObject $persons;

    private Normalizer&MockObject $normalizer;

    protected function setUp(): void
    {
        $this->service = new UpdatePersonInfoService(
            $this->persons = $this->createMock(PersonRepository::class),
            new PersonInfoNormalizer(
                $this->normalizer = $this->createMock(Normalizer::class)
            ),
            new FrozenClock(new DateTimeImmutable('2023-04-01')),
            new PersonAssembler(new AuthAssembler()),
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

        $command = new UpdatePersonInfo(
            '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b',
            $this->makeInfoDto(),
            AuthFaker::fakeFootprintDto(),
        );

        $this->service->execute($command);
    }

    /** @test */
    public function it_updates_person_info(): void
    {
        $personId = PersonId::fromString('6deb7e98-b55d-4214-98ac-fc3a16e3ec6b');
        $person = PersonFaker::fakePerson(
            id: '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b',
            firstname: 'firstname',
            lastname: 'lastname',
        );

        $this->persons
            ->expects($this->once())
            ->method('lockById')
            ->with($this->equalTo($personId))
            ->willReturn($person)
        ;

        $this->persons->expects($this->once())->method('update');

        $this->normalizer
            ->expects($this->exactly(2))
            ->method('normalize')
            ->willReturnOnConsecutiveCalls('normalized test firstname', 'normalized test lastname')
        ;

        $command = new UpdatePersonInfo(
            '6deb7e98-b55d-4214-98ac-fc3a16e3ec6b',
            $this->makeInfoDto(),
            AuthFaker::fakeFootprintDto(),
        );

        $person = $this->service->execute($command);

        $this->assertEquals('normalized test firstname', $person->firstname);
        $this->assertEquals('normalized test lastname', $person->lastname);
        $this->assertEquals(1990, $person->yearOfBirthday);
    }

    private function makeInfoDto(): PersonInfoDto
    {
        $info = new PersonInfoDto();
        $info->firstname = 'test firstname';
        $info->lastname = 'test lastname';
        $info->yearOfBirthday = '1990';

        return $info;
    }
}
