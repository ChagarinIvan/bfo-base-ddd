<?php

declare(strict_types=1);

namespace Tests\Bridge\Laravel\Http\Controller\Competition;

use App\Bridge\Laravel\Http\Controller\Competition\CompetitionController;
use Database\Seeders\Fakes\CompetitionFakeSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class CompetitionControllerTest extends TestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(CompetitionFakeSeeder::class);
    }

    /**
     * @test
     * @see CompetitionController::view()
     */
    public function it_gets_competition(): void
    {
        $this
            ->get('rest/competition/competition/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
                'name' => 'test competition',
                'description' => 'test competition description',
                'from' => '2023-02-01',
                'to' => '2023-02-02',
                'created' => ['at' => '2022-01-01 00:00:00', 'by' => ['id' => '00000000-0000-0000-0000-000000000000']],
                'updated' => ['at' => '2022-01-01 00:00:00', 'by' => ['id' => '00000000-0000-0000-0000-000000000000']],
            ])
        ;
    }

    /**
     * @test
     * @see CompetitionController::list()
     */
    public function it_gets_list_of_competitions(): void
    {
        $this
            ->get('rest/competition/competition?page=1&perPage=2')
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
                    'name',
                    'description',
                    'from',
                    'to',
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
     * @see CompetitionController::list()
     */
    public function it_gets_list_of_competitions_with_filtering(): void
    {
        $this
            ->get('rest/competition/competition?name=1&to=2023-02-02')
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonIsArray()
            ->assertJsonCount(1)
            ->assertHeader('X-Count', 1)
            ->assertHeader('X-Page', 1)
            ->assertHeader('X-Per-Page', 20)
            ->assertHeader('X-Total-Count', 1)
            ->assertJsonStructure([
                [
                    'id',
                    'name',
                    'description',
                    'from',
                    'to',
                    'created' => ['at', 'by'],
                    'updated' => ['at', 'by'],
                ],
            ])
            ->assertJson([
                ['id' => '1b07ca91-1e16-4b5b-b459-341ca9e79aa9'],
            ])
        ;
    }

    /**
     * @test
     * @see CompetitionController::view()
     */
    public function it_returns_not_found_when_competition_disabled(): void
    {
        $this
            ->get('rest/competition/competition/ff4d49a6-e5f0-49ea-8c5c-0bba1200aa97')
            ->assertNotFound()
        ;
    }

    /**
     * @test
     * @see CompetitionController::create()
     */
    public function it_creates_competition(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->post('rest/competition/competition', [
                'name' => 'new name',
                'description' => 'new description',
                'from' => '2023-02-01',
                'to' => '2023-02-02',
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'name' => 'new name',
                'description' => 'new description',
                'from' => '2023-02-01',
                'to' => '2023-02-02',
                'created' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
                'updated' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
            ])
        ;
    }

    /**
     * @test
     * @see CompetitionController::changeCompetitionInfo()
     */
    public function it_changes_competition_info(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->put('rest/competition/competition/1fc7e705-ef72-47b2-ba4e-55779b02c61f', [
                'name' => 'new name',
                'description' => 'new description',
                'from' => '2023-02-01',
                'to' => '2023-02-02',
            ])
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent()
        ;

        $this
            ->get('rest/competition/competition/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
                'name' => 'new name',
                'description' => 'new description',
                'from' => '2023-02-01',
                'to' => '2023-02-02',
                'created' => ['at' => '2022-01-01 00:00:00', 'by' => ['id' => '00000000-0000-0000-0000-000000000000']],
                'updated' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
            ])
        ;
    }

    /**
     * @test
     * @see CompetitionController::changeCompetitionInfo()
     */
    public function it_validates_competition_info_before_update(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->put('rest/competition/competition/1fc7e705-ef72-47b2-ba4e-55779b02c61f', [
                'name' => 'new name',
                'description' => 'new description',
                'to' => '2023-02-02',
            ])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
        ;
    }

    /**
     * @test
     * @see CompetitionController::disable()
     */
    public function it_deletes_competition(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->delete('rest/competition/competition/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent()
        ;

        $this
            ->get('rest/competition/competition/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_NOT_FOUND)
        ;
    }
}
