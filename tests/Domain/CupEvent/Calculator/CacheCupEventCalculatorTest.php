<?php

declare(strict_types=1);

namespace Tests\Domain\CupEvent\Calculator;

use App\Domain\Cup\Group\CupGroup;
use App\Domain\Cup\Group\GroupAge;
use App\Domain\Cup\Group\GroupMale;
use App\Domain\CupEvent\Calculator\CacheCupEventCalculator;
use App\Domain\CupEvent\Calculator\CupEventCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Tests\Faker\CupEvent\CupEventFaker;

class CacheCupEventCalculatorTest extends TestCase
{
    private CacheCupEventCalculator $calculator;

    private CacheInterface&MockObject $cache;

    private CupEventCalculator&MockObject $decorated;

    protected function setUp(): void
    {
        $this->calculator = new CacheCupEventCalculator(
            $this->decorated = $this->createMock(CupEventCalculator::class),
            $this->cache = $this->createMock(CacheInterface::class),
        );
    }

    /** @test */
    public function it_returns_cached_value(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('1250e70c975868bef2a8208e65644c61')
            ->willReturn(['key' => 'value'])
        ;

        $this->decorated
            ->expects($this->never())
            ->method('calculate')
        ;

        $cupEvent = CupEventFaker::fakeCupEvent();
        $this->calculator->calculate($cupEvent, CupGroup::create(GroupMale::Woman, GroupAge::a21));
    }

    /** @test */
    public function it_delegates_calculation_if_cache_not_hit(): void
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('1250e70c975868bef2a8208e65644c61')
        ;

        $cupEvent = CupEventFaker::fakeCupEvent();
        $cupGroup = CupGroup::create(GroupMale::Woman, GroupAge::a21);
        $this->decorated
            ->expects($this->once())
            ->method('calculate')
            ->with($this->equalTo($cupEvent), $this->equalTo($cupGroup))
            ->willReturn([])
        ;

        $this->calculator->calculate($cupEvent, $cupGroup);
    }
}
