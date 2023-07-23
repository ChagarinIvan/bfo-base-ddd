<?php

declare(strict_types=1);

namespace Tests\Domain\CupEvent\Calculator;

use App\Domain\Cup\Group\CupGroup;
use App\Domain\Cup\Group\GroupAge;
use App\Domain\Cup\Group\GroupMale;
use App\Domain\CupEvent\Calculator\EliteCupEventCalculator;
use App\Domain\CupEvent\Calculator\Exception\HasNoDistances;
use App\Domain\CupEvent\Calculator\Exception\IncompleteProtocolLine;
use App\Domain\CupEvent\CupEventPoints;
use App\Domain\Distance\DistanceId;
use App\Domain\ProtocolLine\ProtocolLineRepository;
use App\Domain\Shared\Criteria;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\CupEvent\CupEventFaker;
use Tests\Faker\ProtocolLine\ProtocolLineFaker;

class EliteCupEventCalculatorTest extends TestCase
{
    private EliteCupEventCalculator $calculator;

    private ProtocolLineRepository&MockObject $lines;

    protected function setUp(): void
    {
        $this->calculator = new EliteCupEventCalculator(
            $this->lines = $this->createMock(ProtocolLineRepository::class),
        );
    }

    /** @test */
    public function it_fails_when_event_has_no_distance_for_cup_event(): void
    {
        $this->expectException(HasNoDistances::class);
        $this->expectExceptionMessage('Cup event has no distances for group "W_21".');

        $cupEvent = CupEventFaker::fakeCupEvent();
        $this->calculator->calculate($cupEvent, CupGroup::create(GroupMale::Woman, GroupAge::a21));
    }

    /** @test */
    public function it_fails_when_protocol_line_in_incomplete_state(): void
    {
        $this->expectException(IncompleteProtocolLine::class);
        $this->expectExceptionMessage('Protocol line "1fc7e705-ef72-47b2-ba4e-55779b02c61f" in incomplete state.');

        $this->lines
            ->expects($this->once())
            ->method('byCriteria')
            ->with($this->equalTo(new Criteria([
                'distanceIdIn' => [
                    DistanceId::fromString('b5f58bfd-1335-4e0c-8233-7dc2ab82181f'),
                    DistanceId::fromString('bb3bf8fc-929b-4769-9dad-9fc147a5b87f'),
                ],
                'payed' => true,
                'outOfCompetition' => false,
            ])))
            ->willReturn([
                ProtocolLineFaker::fakeProtocolLine(time: '00:15:32'),
            ])
        ;

        $cupEvent = CupEventFaker::fakeCupEvent();
        $this->calculator->calculate($cupEvent, CupGroup::create(GroupMale::Man, GroupAge::a21));
    }

    /** @test */
    public function it_calculates_cup_event_points(): void
    {
        $cupEvent = CupEventFaker::fakeCupEvent();
        $this->lines
            ->expects($this->once())
            ->method('byCriteria')
            ->with($this->equalTo(new Criteria([
                'distanceIdIn' => [
                    DistanceId::fromString('b5f58bfd-1335-4e0c-8233-7dc2ab82181f'),
                    DistanceId::fromString('bb3bf8fc-929b-4769-9dad-9fc147a5b87f'),
                ],
                'payed' => true,
                'outOfCompetition' => false,
            ])))
            ->willReturn([
                ProtocolLineFaker::fakeProtocolLine(time: '00:15:32', personId: '38c210d4-43d5-44ad-b1e1-e3cb2d633d87'),
                ProtocolLineFaker::fakeProtocolLine(time: '00:16:14', personId: 'f06011ef-9c41-433f-8b1a-2f11abaf2777'),
                ProtocolLineFaker::fakeProtocolLine(time: '00:16:20', personId: '93be4b56-e5ae-4cd8-a773-29a7c53aaf8e'),
                ProtocolLineFaker::fakeProtocolLine(time: '00:18:20'),
                ProtocolLineFaker::fakeProtocolLine(time: '00:22:32', personId: '3e8ad48f-9765-433b-a6c8-8fe43ed108a5'),
                ProtocolLineFaker::fakeProtocolLine(time: '-', personId: 'cd9ab1bd-a261-4f02-9c3e-17e184afaa65'),
            ])
        ;
        $points = $this->calculator->calculate($cupEvent, CupGroup::create(GroupMale::Man, GroupAge::a21));

        $this->assertCount(5, $points);
        $this->assertContainsOnlyInstancesOf(CupEventPoints::class, $points);
        $this->assertEquals(1100, $points[0]->points);
        $this->assertEquals('38c210d4-43d5-44ad-b1e1-e3cb2d633d87', $points[0]->personId->toString());
        $this->assertEquals(1005, $points[1]->points);
        $this->assertEquals('f06011ef-9c41-433f-8b1a-2f11abaf2777', $points[1]->personId->toString());
        $this->assertEquals(992, $points[2]->points);
        $this->assertEquals('93be4b56-e5ae-4cd8-a773-29a7c53aaf8e', $points[2]->personId->toString());
        $this->assertEquals(417, $points[3]->points);
        $this->assertEquals('3e8ad48f-9765-433b-a6c8-8fe43ed108a5', $points[3]->personId->toString());
        $this->assertEquals(0, $points[4]->points);
        $this->assertEquals('cd9ab1bd-a261-4f02-9c3e-17e184afaa65', $points[4]->personId->toString());
    }
}
