<?php

declare(strict_types=1);

namespace Tests\Infrastracture\Laravel\Eloquent\Shared;

use App\Infrastracture\Laravel\Eloquent\Shared\EloquentTransactionalManager;
use Illuminate\Database\ConnectionInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

final class EloquentTransactionalManagerTest extends TestCase
{
    private ConnectionInterface&MockObject $db;
    private EloquentTransactionalManager $manager;

    protected function setUp(): void
    {
        $this->db = $this->createMock(ConnectionInterface::class);
        $this->manager = new EloquentTransactionalManager($this->db);
    }

    /** @test */
    public function it_runs_transaction(): void
    {
        $result = '__result__';

        $fn = static function () use ($result) {
            return $result;
        };

        $this->db
            ->expects($this->once())
            ->method('transaction')
            ->with($this->identicalTo($fn))
            ->willReturn($result)
        ;

        $this->assertEquals($result, $this->manager->run($fn));
    }
}
