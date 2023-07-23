<?php

declare(strict_types=1);

namespace Tests\Application\Service\Cup;

use App\Application\Dto\Cup\CupAssembler;
use App\Application\Dto\Cup\CupDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Cup\AddCup;
use App\Application\Service\Cup\AddCupService;
use App\Domain\Cup\CupInfo;
use App\Domain\Cup\CupRepository;
use App\Domain\Cup\CupType;
use App\Domain\Cup\Factory\CupFactory;
use App\Domain\Cup\Factory\CupInput;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Cup\CupFaker;
use Tests\Faker\Shared\AuthFaker;

class AddCupServiceTest extends TestCase
{
    private AddCupService $service;

    private CupFactory&MockObject $factory;

    private CupRepository&MockObject $cups;

    protected function setUp(): void
    {
        $this->service = new AddCupService(
            $this->factory = $this->createMock(CupFactory::class),
            $this->cups = $this->createMock(CupRepository::class),
            new CupAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_creates_cup(): void
    {
        $input = new CupInput(
            new CupInfo('test cup', 2, 2023, CupType::ELITE),
            AuthFaker::fakeFootprint(),
        );

        $cup = CupFaker::fakeCup();

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo($input))
            ->willReturn($cup)
        ;

        $this->cups
            ->expects($this->once())
            ->method('add')
            ->with($this->identicalTo($cup))
        ;

        $dto = new CupDto();
        $dto->name = 'test cup';
        $dto->eventCounts = 2;
        $dto->year = 2023;
        $dto->type = 'elite';

        $command = new AddCup($dto, AuthFaker::fakeFootprintDto());
        $cupDto = $this->service->execute($command);

        $this->assertEquals($cup->id()->toString(), $cupDto->id);
    }
}
