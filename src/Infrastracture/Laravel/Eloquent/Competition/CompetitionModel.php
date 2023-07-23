<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Competition;

use App\Domain\AggregatedRoot;
use App\Domain\Competition\Competition;
use App\Domain\Competition\CompetitionId;
use App\Domain\Competition\CompetitionInfo;
use App\Infrastracture\Laravel\Eloquent\AggregateModel;
use DateTimeImmutable;
use ReflectionClass;

/**
 * @property string $name
 * @property string $description
 * @property string $from
 * @property string $to
 */
class CompetitionModel extends AggregateModel
{
    protected $table = 'ddd_competition';

    /**
     * @param Competition $root
     */
    public static function fromAggregate(AggregatedRoot $root): static
    {
        $model = parent::fromAggregate($root);
        $model->name = $root->name();
        $model->description = $root->description();
        $model->from = self::dateToString($root->from());
        $model->to = self::dateToString($root->to());

        return $model;
    }

    public function toAggregate(): AggregatedRoot
    {
        $reflector = new ReflectionClass(Competition::class);
        $competition = $reflector->newInstanceWithoutConstructor();
        $reflector = new ReflectionClass($competition);
        self::setWithReflection($reflector, 'id', $competition, CompetitionId::fromString($this->id));
        self::setWithReflection($reflector, 'info', $competition, new CompetitionInfo(
            $this->name,
            $this->description,
            new DateTimeImmutable($this->from),
            new DateTimeImmutable($this->to),
        ));
        self::setWithReflection($reflector, 'disabled', $competition, $this->disabled);
        self::setWithReflection($reflector, 'created', $competition, self::reflectImpression($this->createdAt, $this->createdBy));
        self::setWithReflection($reflector, 'updated', $competition, self::reflectImpression($this->updatedAt, $this->updatedBy));

        return $competition;
    }
}
