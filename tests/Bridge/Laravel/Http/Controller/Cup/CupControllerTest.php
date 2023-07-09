<?php

declare(strict_types=1);

namespace Tests\Bridge\Laravel\Http\Controller\Cup;

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

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(CupFakeSeeder::class);
    }

    /** @test */
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
}
