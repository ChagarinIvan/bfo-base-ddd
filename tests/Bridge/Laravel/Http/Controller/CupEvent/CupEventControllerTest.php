<?php

declare(strict_types=1);

namespace Tests\Bridge\Laravel\Http\Controller\CupEvent;

use App\Bridge\Laravel\Http\Controller\CupEvent\CupEventController;
use Database\Seeders\Fakes\CupEventFakeSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class CupEventControllerTest extends TestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    /** @return array<string, array<int, mixed>> */
    public static function validationTestDataProvider(): array
    {
        return [
            'validate_cup_id' => ['test'],
            'validate_event_id' => ['bb3bf8fc-929b-4769-9dad-9fc147a5b87f', 'test'],
            'validate_points' => ['bb3bf8fc-929b-4769-9dad-9fc147a5b87f', 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f', 'test'],
            'validate_groups_map_format' => ['bb3bf8fc-929b-4769-9dad-9fc147a5b87f', 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f', '0.9', 'test'],
            'validate_groups_map_group_key' => ['bb3bf8fc-929b-4769-9dad-9fc147a5b87f', 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f', '0.9', [['group_id' => 'M21', 'distances' => ['bb3bf8fc-929b-4769-9dad-9fc147a5b87f']]]],
            'validate_groups_map_distance_id' => ['bb3bf8fc-929b-4769-9dad-9fc147a5b87f', 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f', '0.9', [['group_id' => 'M21', 'distances' => ['test']]]],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function listCriteriaDataProvider(): array
    {
        return [
            'filter_by_event_id' => ['eventId=bb3bf8fc-929b-4769-9dad-9fc147a5b87f', '1b07ca91-1e16-4b5b-b459-341ca9e79aa9'],
            'filter_by_cup_id' => ['cupId=b5f58bfd-1335-4e0c-8233-7dc2ab82181f', '3a48ca7e-13bc-4198-80ba-237384dbf9a6'],
        ];
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(CupEventFakeSeeder::class);
    }

    /**
     * @test
     */
    public function calculate(): void
    {
    }

    /**
     * @test
     * @see CupEventController::create()
     */
    public function it_creates_cup_event(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->post('rest/cup-event/cup-event', [
                'cupId' => 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f',
                'eventId' => 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f',
                'points' => '2023',
                'groupsDistances' => [['group_id' => 'W_18', 'distances' => ['bb3bf8fc-929b-4769-9dad-9fc147a5b87f']]],
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'cupId' => 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f',
                'eventId' => 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f',
                'points' => '2023',
                'groupsDistances' => [['group_id' => 'W_18', 'distances' => ['bb3bf8fc-929b-4769-9dad-9fc147a5b87f']]],
                'created' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
                'updated' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
            ])
        ;
    }

    /**
     * @test
     * @param array<string, array<int, mixed>> $groupsDistances
     * @dataProvider validationTestDataProvider
     * @see CupEventController::create()
     */
    public function it_validates_cup_event(
        string $cupId = 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f',
        string $eventId = 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f',
        string $points = '1100',
        string|array $groupsDistances = [],
    ): void {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->post('rest/cup-event/cup-event', [
                'cupId' => $cupId,
                'eventId' => $eventId,
                'points' => $points,
                'groupsDistances' => $groupsDistances,
            ])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
        ;
    }

    /**
     * @test
     * @see CupEventController::changeCupInfo()
     */
    public function it_changes_cup_event_attributes(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->put('rest/cup-event/cup-event/1fc7e705-ef72-47b2-ba4e-55779b02c61f', [
                'points' => '0.9',
                'groupsDistances' => [['group_id' => 'W_18', 'distances' => ['bb3bf8fc-929b-4769-9dad-9fc147a5b87f']]],
            ])
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent()
        ;

        $this
            ->get('rest/cup-event/cup-event/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
                'cupId' => '1efaf3e4-a661-4a68-b014-669e03d1f895',
                'eventId' => '56e6fb03-5869-427e-9bd3-14d8695500cf',
                'points' => '0.9',
                'groupsDistances' => [['group_id' => 'W_18', 'distances' => ['bb3bf8fc-929b-4769-9dad-9fc147a5b87f']]],
                'created' => ['at' => '2022-01-01 00:00:00', 'by' => ['id' => '00000000-0000-0000-0000-000000000000']],
                'updated' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
            ])
        ;
    }

    /**
     * @test
     * @see CupEventController::view()
     */
    public function it_gets_cup_event(): void
    {
        $this
            ->get('rest/cup-event/cup-event/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
                'cupId' => '1efaf3e4-a661-4a68-b014-669e03d1f895',
                'eventId' => '56e6fb03-5869-427e-9bd3-14d8695500cf',
                'points' => '1100',
                'groupsDistances' => [['group_id' => 'M_21', 'distances' => ['b5f58bfd-1335-4e0c-8233-7dc2ab82181f', 'bb3bf8fc-929b-4769-9dad-9fc147a5b87f']]],
                'created' => ['at' => '2022-01-01 00:00:00', 'by' => ['id' => '00000000-0000-0000-0000-000000000000']],
                'updated' => ['at' => '2022-01-01 00:00:00', 'by' => ['id' => '00000000-0000-0000-0000-000000000000']],
            ])
        ;
    }

    /**
     * @test
     * @see CupEventController::list()
     */
    public function it_gets_list_of_cups_events_with_pagination(): void
    {
        $this
            ->get('rest/cup-event/cup-event?page=1&perPage=2')
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonIsArray()
            ->assertJsonCount(2)
            ->assertHeader('X-Count', 2)
            ->assertHeader('X-Page', 1)
            ->assertHeader('X-Per-Page', 2)
            ->assertHeader('X-Total-Count', 3)
            ->assertJsonStructure([
                [
                    'id',
                    'cupId',
                    'eventId',
                    'points',
                    'groupsDistances',
                    'created' => ['at', 'by'],
                    'updated' => ['at', 'by'],
                ],
            ])
            ->assertJson([
                ['id' => '1b07ca91-1e16-4b5b-b459-341ca9e79aa9'],
                ['id' => '3a48ca7e-13bc-4198-80ba-237384dbf9a6'],
            ])
        ;
    }

    /**
     * @test
     * @dataProvider listCriteriaDataProvider
     * @see CupEventController::list()
     */
    public function it_gets_list_of_cup_events_with_filtering(string $filter, string $id): void
    {
        $this
            ->get('rest/cup-event/cup-event?page=1&perPage=1&' . $filter)
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonIsArray()
            ->assertJsonCount(1)
            ->assertHeader('X-Count', 1)
            ->assertHeader('X-Page', 1)
            ->assertHeader('X-Per-Page', 1)
            ->assertHeader('X-Total-Count', 1)
            ->assertJsonStructure([
                [
                    'id',
                    'cupId',
                    'eventId',
                    'points',
                    'groupsDistances',
                    'created' => ['at', 'by'],
                    'updated' => ['at', 'by'],
                ],
            ])
            ->assertJson([
                ['id' => $id],
            ])
        ;
    }

    /**
     * @test
     * @see CupEventController::disable()
     */
    public function it_deletes_cup_event(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->delete('rest/cup-event/cup-event/3a48ca7e-13bc-4198-80ba-237384dbf9a6')
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent()
        ;

        $this
            ->get('rest/cup-event/cup-event/3a48ca7e-13bc-4198-80ba-237384dbf9a6')
            ->assertStatus(Response::HTTP_NOT_FOUND)
        ;
    }
}
