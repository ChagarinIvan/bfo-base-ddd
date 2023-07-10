<?php

declare(strict_types=1);

namespace Tests\Bridge\Laravel\Http\Controller\Club;

use App\Bridge\Laravel\Http\Controller\Club\ClubController;
use Database\Seeders\Fakes\ClubFakeSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class ClubControllerTest extends TestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(ClubFakeSeeder::class);
    }

    /**
     * @test
     * @see ClubController::list()
     */
    public function it_gets_list_of_clubs(): void
    {
        $this
            ->get('rest/club/club?page=1&perPage=2')
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
                    'created' => ['at', 'by'],
                    'updated' => ['at', 'by'],
                ],
            ])
            ->assertJson([
                ['id' => 'a25c7b35-dae6-4d92-8f62-d3dcdfa22f51'],
                ['id' => 'ab79834f-494b-4137-9280-eb496328addf'],
            ])
        ;
    }

    /**
     * @test
     * @see ClubController::list()
     */
    public function it_gets_list_of_clubs_with_filtering(): void
    {
        $this
            ->get('rest/club/club?name=3')
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
                    'created' => ['at', 'by'],
                    'updated' => ['at', 'by'],
                ],
            ])
            ->assertJson([
                ['id' => 'ab79834f-494b-4137-9280-eb496328addf'],
            ])
        ;
    }

    /**
     * @test
     * @see ClubController::view()
     */
    public function it_returns_not_found_when_club_disabled(): void
    {
        $this
            ->get('rest/club/club/ff4d49a6-e5f0-49ea-8c5c-0bba1200aa97')
            ->assertNotFound()
        ;
    }

    /**
     * @test
     * @see ClubController::create()
     */
    public function it_creates_club(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->post('rest/club/club', [
                'name' => 'nеw name',
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'name' => 'nеw nаmе',
                'created' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
                'updated' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
            ])
        ;
    }

    /**
     * @test
     * @see ClubController::changeClub()
     */
    public function it_changes_club(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->put('rest/club/club/1fc7e705-ef72-47b2-ba4e-55779b02c61f', [
                'name' => 'updated name',
            ])
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent()
        ;

        $this
            ->get('rest/club/club/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
                'name' => 'uрdаtеd nаmе',
                'created' => ['at' => '2022-01-01 00:00:00', 'by' => ['id' => '00000000-0000-0000-0000-000000000000']],
                'updated' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
            ])
        ;
    }

    /**
     * @test
     * @see ClubController::create()
     */
    public function it_prevent_creating_of_duplicate_club(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->post('rest/club/club', [
                'name' => 'test club',
            ])
            ->assertStatus(Response::HTTP_CONFLICT)
        ;
    }

    /**
     * @test
     * @see ClubController::create()
     */
    public function it_validates_club_name_before_create(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->post('rest/club/club', [
                'name' => 'test club real long more then 255 test club real long more then 255 test club real long more then 255 test club real long more then 255 test club real long more then 255 test club real long more then 255 test club real long more then 255 test club real long more then 255',
            ])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
        ;
    }

    /**
     * @test
     * @see ClubController::disable()
     */
    public function it_deletes_club(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->delete('rest/club/club/a25c7b35-dae6-4d92-8f62-d3dcdfa22f51')
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent()
        ;

        $this
            ->get('rest/club/club/a25c7b35-dae6-4d92-8f62-d3dcdfa22f51')
            ->assertStatus(Response::HTTP_NOT_FOUND)
        ;
    }
}
