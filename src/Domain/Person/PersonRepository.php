<?php

declare(strict_types=1);

namespace App\Domain\Person;

use App\Domain\Shared\Criteria;

interface PersonRepository
{
    public function add(Person $event): void;

    public function lockById(PersonId $id): ?Person;

    public function update(Person $event): void;

    public function oneByCriteria(Criteria $criteria): ?Person;
}
