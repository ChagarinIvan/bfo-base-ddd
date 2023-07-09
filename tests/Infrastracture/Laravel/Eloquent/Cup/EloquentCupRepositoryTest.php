<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Eloquent\Cup;

use App\Infrastracture\Laravel\Eloquent\Cup\EloquentCupRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Faker\Cup\CupFaker;
use Tests\TestCase;

class EloquentCupRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private EloquentCupRepository $cups;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cups = new EloquentCupRepository();
    }

    /** @test */
    public function it_adds_competition_in_db(): void
    {
        $cup = CupFaker::fakeCup();
        $this->assertDatabaseEmpty('ddd_cup');
        $this->cups->add($cup);
        $this->assertDatabaseCount('ddd_cup', 1);
    }
}
