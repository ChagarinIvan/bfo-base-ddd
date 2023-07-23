<?php

declare(strict_types=1);

namespace Tests\Bridge\Laravel\Http\Controller\Cup;

use App\Bridge\Laravel\Http\Controller\Cup\CupController;
use Database\Seeders\Fakes\CupFakeSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class CupControllerTest extends TestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    /** @return array<int, string[]> */
    public static function validationTestDataProvider(): array
    {
        return [
            ['test club real long more then 255 test club real long more then 255 test club real long more then 255 test club real long more then 255 test club real long more then 255 test club real long more then 255 test club real long more then 255 test club real long more then 255'],
            ['new cup', 'test'],
            ['new cup', '3', '22'],
            ['new cup', '3', '2023', 'master1'],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function listCriteriaDataProvider(): array
    {
        return [
            'filter_by_name' => ['name=2', '3a48ca7e-13bc-4198-80ba-237384dbf9a6'],
            'filter_by_type' => ['type=bike', '1b07ca91-1e16-4b5b-b459-341ca9e79aa9'],
            'filter_by_year' => ['year=2021', '1b07ca91-1e16-4b5b-b459-341ca9e79aa9'],
        ];
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(CupFakeSeeder::class);
    }

    /**
     * @test
     * @see CupController::view()
     */
    public function it_gets_cup(): void
    {
        $this
            ->get('rest/cup/cup/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
                'name' => 'cup name',
                'eventsCount' => 3,
                'year' => 2023,
                'type' => 'elite',
                'created' => ['at' => '2022-01-01 00:00:00', 'by' => ['id' => '00000000-0000-0000-0000-000000000000']],
                'updated' => ['at' => '2022-01-01 00:00:00', 'by' => ['id' => '00000000-0000-0000-0000-000000000000']],
            ])
        ;
    }

    /**
     * @test
     * @see CupController::list()
     */
    public function it_gets_list_of_cups(): void
    {
        $this
            ->get('rest/cup/cup?page=1&perPage=2')
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
                    'eventsCount',
                    'type',
                    'year',
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
     * @see CupController::list()
     */
    public function it_gets_list_of_cups_with_filtering(string $filter, string $id): void
    {
        $this
            ->get('rest/cup/cup?page=1&perPage=1&' . $filter)
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
                    'name',
                    'eventsCount',
                    'type',
                    'year',
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
     * @see CupController::create()
     */
    public function it_creates_cup(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->post('rest/cup/cup', [
                'name' => 'new cup',
                'eventsCount' => '3',
                'year' => '2023',
                'type' => 'master',
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'name' => 'new cup',
                'eventsCount' => '3',
                'year' => '2023',
                'type' => 'master',
                'created' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
                'updated' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
            ])
        ;
    }

    /**
     * @test
     * @see CupController::create()
     * @dataProvider validationTestDataProvider
     */
    public function it_validates_cup(
        string $name = 'new cup',
        string $eventsCount = '3',
        string $year = '2023',
        string $type = 'master',
    ): void {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->post('rest/cup/cup', [
                'name' => $name,
                'eventsCount' => $eventsCount,
                'year' => $year,
                'type' => $type,
            ])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
        ;
    }

    /**
     * @test
     * @see CupController::changeCupInfo()
     */
    public function it_changes_cup_info(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->put('rest/cup/cup/1fc7e705-ef72-47b2-ba4e-55779b02c61f', [
                'name' => 'new name',
                'year' => '2023',
                'eventsCount' => '7',
                'type' => 'elite',
            ])
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent()
        ;

        $this
            ->get('rest/cup/cup/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_OK)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'id' => '1fc7e705-ef72-47b2-ba4e-55779b02c61f',
                'name' => 'new name',
                'year' => '2023',
                'eventsCount' => '7',
                'type' => 'elite',
                'created' => ['at' => '2022-01-01 00:00:00', 'by' => ['id' => '00000000-0000-0000-0000-000000000000']],
                'updated' => ['at' => '2023-03-03 00:00:00', 'by' => ['id' => '8ea50008-9041-40a9-8351-2c7ecbe322a9']],
            ])
        ;
    }

    /**
     * @test
     * @see CupController::disable()
     */
    public function it_deletes_cup(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZWE1MDAwOC05MDQxLTQwYTktODM1MS0yYzdlY2JlMzIyYTkifQ.xW2Ln43xqo8fU1GPCBq7ZXIxbZ3UhtphUFG_wRF9Nok')
            ->delete('rest/cup/cup/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent()
        ;

        $this
            ->get('rest/cup/cup/1fc7e705-ef72-47b2-ba4e-55779b02c61f')
            ->assertStatus(Response::HTTP_NOT_FOUND)
        ;
    }
}
