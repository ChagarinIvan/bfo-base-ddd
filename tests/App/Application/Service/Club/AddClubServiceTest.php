<?php

declare(strict_types=1);

namespace Tests\Application\Service\Club;

use App\Application\Dto\Club\ClubAssembler;
use App\Application\Dto\Club\ClubDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Club\AddClub;
use App\Application\Service\Club\AddClubService;
use App\Application\Service\Club\Exception\FailedToAddClub;
use App\Domain\Club\ClubRepository;
use App\Domain\Club\Exception\ClubAlreadyExist;
use App\Domain\Club\Factory\ClubFactory;
use App\Domain\Club\Factory\ClubInput;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Club\ClubFaker;
use Tests\Faker\Shared\AuthFaker;

class AddClubServiceTest extends TestCase
{
    private AddClubService $service;

    private ClubFactory&MockObject $factory;

    private ClubRepository&MockObject $clubs;

    protected function setUp(): void
    {
        $this->service = new AddClubService(
            $this->factory = $this->createMock(ClubFactory::class),
            $this->clubs = $this->createMock(ClubRepository::class),
            new ClubAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_fails_on_exists_duplicate_club(): void
    {
        $this->expectException(FailedToAddClub::class);
        $this->expectExceptionMessage('Unable to add club. Reason: Error.');

        $input = new ClubInput('test club', AuthFaker::fakeFootprint());
        $club = ClubFaker::fakeClub();

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo($input))
            ->willThrowException(new ClubAlreadyExist('Error.'))
        ;

        $this->clubs->expects($this->never())->method('add');

        $dto = new ClubDto();
        $dto->name = 'test club';

        $command = new AddClub($dto, AuthFaker::fakeFootprintDto());
        $clubDto = $this->service->execute($command);

        $this->assertEquals($club->id()->toString(), $clubDto->id);
    }

    /** @test */
    public function it_creates_club(): void
    {
        $input = new ClubInput('test club', AuthFaker::fakeFootprint());
        $club = ClubFaker::fakeClub();

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo($input))
            ->willReturn($club)
        ;

        $this->clubs
            ->expects($this->once())
            ->method('add')
            ->with($this->identicalTo($club))
        ;

        $dto = new ClubDto();
        $dto->name = 'test club';

        $command = new AddClub($dto, AuthFaker::fakeFootprintDto());
        $clubDto = $this->service->execute($command);

        $this->assertEquals($club->id()->toString(), $clubDto->id);
    }
}
