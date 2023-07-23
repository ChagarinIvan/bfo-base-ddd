<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Club;

use App\Domain\AggregatedRoot;
use App\Domain\Club\Club;
use App\Domain\Club\ClubId;
use App\Infrastracture\Laravel\Eloquent\AggregateModel;
use ReflectionClass;

/**
 * @property string $name
 */
class ClubModel extends AggregateModel
{
    protected $table = 'ddd_club';

    /**
     * @param Club $root
     */
    public static function fromAggregate(AggregatedRoot $root): static
    {
        $model = parent::fromAggregate($root);
        $model->name = $root->name();

        return $model;
    }

    public function toAggregate(): AggregatedRoot
    {
        $reflector = new ReflectionClass(Club::class);
        $club = $reflector->newInstanceWithoutConstructor();
        $reflector = new ReflectionClass($club);
        self::setWithReflection($reflector, 'id', $club, ClubId::fromString($this->id));
        self::setWithReflection($reflector, 'name', $club, $this->name);
        self::setWithReflection($reflector, 'disabled', $club, $this->disabled);
        self::setWithReflection($reflector, 'created', $club, self::reflectImpression($this->createdAt, $this->createdBy));
        self::setWithReflection($reflector, 'updated', $club, self::reflectImpression($this->updatedAt, $this->updatedBy));

        return $club;
    }
}
