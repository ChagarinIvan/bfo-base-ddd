<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Cup;

use App\Domain\AggregatedRoot;
use App\Domain\Cup\Cup;
use App\Domain\Cup\CupId;
use App\Domain\Cup\CupInfo;
use App\Domain\Cup\CupType;
use App\Infrastracture\Laravel\Eloquent\AggregateModel;
use ReflectionClass;

/**
 * @property string $name
 * @property int $eventsCount
 * @property int $year
 * @property string $type
 */
class CupModel extends AggregateModel
{
    protected $table = 'ddd_cup';

    /**
     * @param Cup $root
     */
    public static function fromAggregate(AggregatedRoot $root): static
    {
        $model = parent::fromAggregate($root);
        $model->name = $root->name();
        $model->eventsCount = $root->eventsCount();
        $model->year = $root->year();
        $model->type = $root->type()->toString();

        return $model;
    }

    public function toAggregate(): AggregatedRoot
    {
        $reflector = new ReflectionClass(Cup::class);
        $cup = $reflector->newInstanceWithoutConstructor();
        $reflector = new ReflectionClass($cup);
        self::setWithReflection($reflector, 'id', $cup, CupId::fromString($this->id));
        self::setWithReflection($reflector, 'info', $cup, new CupInfo(
            $this->name,
            $this->eventsCount,
            $this->year,
            CupType::from($this->type),
        ));
        self::setWithReflection($reflector, 'disabled', $cup, $this->disabled);
        self::setWithReflection($reflector, 'created', $cup, self::reflectImpression($this->createdAt, $this->createdBy));
        self::setWithReflection($reflector, 'updated', $cup, self::reflectImpression($this->updatedAt, $this->updatedBy));

        return $cup;
    }
}
