<?php

declare(strict_types=1);

namespace Tests\Application\Dto\Person;

use App\Application\Dto\Person\PersonAssembler;
use App\Application\Dto\Shared\AuthAssembler;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as BaseUuid;
use Tests\Faker\Person\PersonFaker;

class PersonAssemblerTest extends TestCase
{
    private readonly PersonAssembler $assembler;

    protected function setUp(): void
    {
        $this->assembler = new PersonAssembler(new AuthAssembler());
    }

    /** @test */
    public function it_assembles_person_to_dto(): void
    {
        $person = PersonFaker::fakePerson(clubId: 'f0fcdfd7-926c-4b8e-901f-5ba860bbed42');
        $dto = $this->assembler->toViewPersonDto($person);

        $this->assertEquals('1fc7e705-ef72-47b2-ba4e-55779b02c61f', $dto->id);
        $this->assertEquals('test firstname', $dto->firstname);
        $this->assertEquals('test lastname', $dto->lastname);
        $this->assertEquals('1988', $dto->yearOfBirthday);
        $this->assertEquals('f0fcdfd7-926c-4b8e-901f-5ba860bbed42', $dto->clubId);
        $this->assertNull($dto->activeRankId);
        $this->assertEmpty($dto->attributes);
        $this->assertEquals('2022-01-01 00:00:00', $dto->created->at);
        $this->assertEquals(BaseUuid::NIL, $dto->created->by->id);
        $this->assertEquals('2022-01-01 00:00:00', $dto->updated->at);
        $this->assertEquals(BaseUuid::NIL, $dto->updated->by->id);
    }
}
