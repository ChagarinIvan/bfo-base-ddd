<?php

declare(strict_types=1);

namespace Tests\Bridge\Laravel\Http\Controller\Event;

use Database\Seeders\Fakes\EventFakeSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(EventFakeSeeder::class);
    }

    /** @test */
    public function it_gets_event(): void
    {
        $this
            ->get('rest/event/event/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
                'competitionId' => '1efaf3e4-a661-4a68-b014-669e03d1f895',
                'name' => 'test event',
                'description' => 'test event description',
                'date' => '2023-01-01',
                'created' => ['at' => '2022-01-01 00:00:00', 'by' => ['id' => '00000000-0000-0000-0000-000000000000']],
                'updated' => ['at' => '2022-01-01 00:00:00', 'by' => ['id' => '00000000-0000-0000-0000-000000000000']],
            ])
        ;
    }

    /** @test */
    public function it_returns_not_found_when_event_disabled(): void
    {
        $this->get('rest/event/event/ff4d49a6-e5f0-49ea-8c5c-0bba1200aa97')->assertNotFound();
    }

    /** @test */
    public function it_creates_event(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->post('rest/event/event', [
                'name' => 'new event',
                'description' => 'new event description',
                'date' => '2023-02-01',
                'competitionId' => '1efaf3e4-a661-4a68-b014-669e03d1f895',
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'name' => 'new event',
                'description' => 'new event description',
                'competitionId' => '1efaf3e4-a661-4a68-b014-669e03d1f895',
                'date' => '2023-02-01',
                'created' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
                'updated' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
            ])
        ;
    }

    /** @test */
    public function it_changes_event_info(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->put('rest/event/event/1fc7e705-ef72-47b2-ba4e-55779b02c61f', [
                'name' => 'new name',
                'description' => 'new description',
                'date' => '2022-02-03',
            ])
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent()
        ;

        $this
            ->get('rest/event/event/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
                'name' => 'new name',
                'description' => 'new description',
                'date' => '2022-02-03',
                'created' => ['at' => '2022-01-01 00:00:00', 'by' => ['id' => '00000000-0000-0000-0000-000000000000']],
                'updated' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
            ])
        ;
    }

    /** @test */
    public function it_validates_event_info_before_update(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->put('rest/event/event/1fc7e705-ef72-47b2-ba4e-55779b02c61f', [
                'name' => 'new name',
                'description' => 'new description',
                'to' => '2023-02-02',
            ])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
        ;
    }

    /** @test */
    public function it_deletes_event(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->delete('rest/event/event/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent()
        ;

        $this
            ->get('rest/event/event/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_NOT_FOUND)
        ;
    }
}