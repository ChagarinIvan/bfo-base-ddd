<?php

declare(strict_types=1);

namespace App\Domain\CupEvent\Calculator;

use App\Domain\Cup\Group\CupGroup;
use App\Domain\CupEvent\CupEvent;
use Psr\SimpleCache\CacheInterface;
use function md5;

final readonly class CacheCupEventCalculator implements CupEventCalculator
{
    public function __construct(private CupEventCalculator $decorated, private CacheInterface $cache)
    {
    }

    public function calculate(CupEvent $cupEvent, CupGroup $group): array
    {
        $cacheKey = md5($cupEvent->id()->toString() . $group->id());
        if ($cache = $this->cache->get($cacheKey)) {
            return $cache;
        }

        return $this->decorated->calculate($cupEvent, $group);
    }
}
