<?php

declare(strict_types=1);

namespace Tests\Domain\Person\Factory;

use App\Domain\Person\Exception\PersonInfoAlreadyExist;
use App\Domain\Person\Factory\PersonFactory;
use App\Domain\Person\Factory\PersonInput;
use App\Domain\Person\Factory\PreventDuplicatePersonFactory;
use App\Domain\Person\PersonInfo;
use App\Domain\Person\PersonRepository;
use App\Domain\Shared\Criteria;
use App\Domain\Shared\Metadata;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Person\PersonFaker;
use Tests\Faker\Shared\AuthFaker;

class PreventDuplicatePersonFactoryTest extends TestCase
{
    private readonly PersonFactory&MockObject $decorated;

    private readonly PersonRepository&MockObject $persons;

    private readonly PreventDuplicatePersonFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new PreventDuplicatePersonFactory(
            $this->decorated = $this->createMock(PersonFactory::class),
            $this->persons = $this->createMock(PersonRepository::class),
        );
    }

    /** @test */
    public function it_fails_when_person_with_same_info_already_exist(): void
    {
        $this->expectException(PersonInfoAlreadyExist::class);
        $this->expectExceptionMessage('Person "Test firstname Test lastname - unknown year" already exist.');

        $info = $this->makeInfo();

        $this->decorated->expects($this->never())->method('create');
        $this->persons
            ->expects($this->once())
            ->method('oneByCriteria')
            ->with($this->equalTo(new Criteria(['info' => $info])))
            ->willReturn(PersonFaker::fakePerson())
        ;

        $this->factory->create($this->makeInput($info));
    }

    /** @test */
    public function it_propagates_person_creation_on_equal_person_not_exists(): void
    {
        $info = $this->makeInfo();
        $input = $this->makeInput($info);

        $this->decorated
            ->expects($this->once())
            ->method('create')
            ->with($this->identicalTo($input))
            ->willReturn(PersonFaker::fakePerson())
        ;

        $this->persons
            ->expects($this->once())
            ->method('oneByCriteria')
            ->with($this->equalTo(new Criteria(['info' => $info])))
            ->willReturn(null)
        ;

        $this->factory->create($input);
    }

    private function makeInfo(): PersonInfo
    {
        return new PersonInfo(
            'test firstname',
            'test lastname',
        );
    }

    private function makeInput(PersonInfo $info): PersonInput
    {
        return new PersonInput(
            $info,
            Metadata::empty(),
            AuthFaker::fakeFootprint()
        );
    }
}
