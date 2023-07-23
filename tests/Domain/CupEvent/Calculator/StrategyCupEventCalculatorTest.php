<?php

declare(strict_types=1);

namespace Tests\Domain\CupEvent\Calculator;

use App\Domain\Cup\CupId;
use App\Domain\Cup\CupRepository;
use App\Domain\Cup\Group\CupGroup;
use App\Domain\Cup\Group\GroupAge;
use App\Domain\Cup\Group\GroupMale;
use App\Domain\CupEvent\Calculator\CupEventCalculator;
use App\Domain\CupEvent\Calculator\EliteCupEventCalculator;
use App\Domain\CupEvent\Calculator\Exception\CupNotExist;
use App\Domain\CupEvent\Calculator\StrategyCupEventCalculator;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Cup\CupFaker;
use Tests\Faker\CupEvent\CupEventFaker;

class StrategyCupEventCalculatorTest extends TestCase
{
    private StrategyCupEventCalculator $calculator;

    private CupRepository&MockObject $cups;

    private Container&MockObject $container;

    protected function setUp(): void
    {
        $this->calculator = new StrategyCupEventCalculator(
            $this->cups = $this->createMock(CupRepository::class),
            $this->container = $this->createMock(Container::class),
        );
    }

    /** @test */
    public function it_fails_when_cup_not_exist(): void
    {
        $this->expectException(CupNotExist::class);
        $this->expectExceptionMessage('Cup "1efaf3e4-a661-4a68-b014-669e03d1f895" not found.');
        $this->cups
            ->expects($this->once())
            ->method('byId')
            ->with(CupId::fromString('1efaf3e4-a661-4a68-b014-669e03d1f895'))
        ;

        $cupEvent = CupEventFaker::fakeCupEvent();
        $this->calculator->calculate($cupEvent, CupGroup::create(GroupMale::Woman, GroupAge::a21));
    }

    /** @test */
    public function it_delegates_calculation_to_type_calculator(): void
    {
        $cup = CupFaker::fakeCup(id: '1efaf3e4-a661-4a68-b014-669e03d1f895');
        $this->cups
            ->expects($this->once())
            ->method('byId')
            ->with(CupId::fromString('1efaf3e4-a661-4a68-b014-669e03d1f895'))
            ->willReturn($cup)
        ;

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo(EliteCupEventCalculator::class))
            ->willReturn($this->createMock(CupEventCalculator::class))
        ;

        $cupEvent = CupEventFaker::fakeCupEvent();
        $this->calculator->calculate($cupEvent, CupGroup::create(GroupMale::Woman, GroupAge::a21));
    }
}
