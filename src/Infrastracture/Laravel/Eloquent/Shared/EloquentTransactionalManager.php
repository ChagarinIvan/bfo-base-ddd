<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Shared;

use App\Domain\Shared\TransactionManager;
use Closure;
use Illuminate\Database\ConnectionInterface;

final class EloquentTransactionalManager implements TransactionManager
{
    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function run(Closure $fn): mixed
    {
        return $this->db->transaction($fn);
    }
}
