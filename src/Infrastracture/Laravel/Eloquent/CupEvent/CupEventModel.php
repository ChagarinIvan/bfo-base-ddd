<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\CupEvent;

use App\Application\Dto\CupEvent\CupEventAssembler;
use App\Domain\AggregatedRoot;
use App\Domain\Cup\CupId;
use App\Domain\CupEvent\CupEvent;
use App\Domain\CupEvent\CupEventAttributes;
use App\Domain\CupEvent\CupEventId;
use App\Domain\CupEvent\GroupDistances;
use App\Domain\CupEvent\GroupsDistances;
use App\Domain\Distance\DistanceId;
use App\Domain\Event\EventId;
use App\Infrastracture\Laravel\Eloquent\AggregateModel;
use ReflectionClass;
use function array_map;
use function array_merge;

/**
 * @property string $cupId
 * @property string $eventId
 * @property array $groupsDistances
 * @property string $points
 */
class CupEventModel extends AggregateModel
{
    protected $table = 'ddd_cup_event';

    protected $casts = [
        'groupsDistances' => 'array',
    ];

    /**
     * @param CupEvent $root
     */
    public static function fromAggregate(AggregatedRoot $root): static
    {
        $model = parent::fromAggregate($root);
        $model->cupId = $root->cupId()->toString();
        $model->eventId = $root->eventId()->toString();
        $model->groupsDistances = CupEventAssembler::groupsDistancesToArray($root->groupsDistances());
        $model->points = (string) $root->points();

        return $model;
    }

    public function toAggregate(): AggregatedRoot
    {
        $reflector = new ReflectionClass(CupEvent::class);
        $cupEvent = $reflector->newInstanceWithoutConstructor();
        $reflector = new ReflectionClass($cupEvent);
        self::setWithReflection($reflector, 'id', $cupEvent, CupEventId::fromString($this->id));
        self::setWithReflection($reflector, 'cupId', $cupEvent, CupId::fromString($this->cupId));
        self::setWithReflection($reflector, 'eventId', $cupEvent, EventId::fromString($this->eventId));
        self::setWithReflection($reflector, 'attributes', $cupEvent, new CupEventAttributes(
            new GroupsDistances(array_map(
                static fn (array $data): GroupDistances => new GroupDistances(
                    $data['group_id'],
                    array_map(DistanceId::fromString(...), $data['distances'])
                ),
                $this->groupsDistances
            )),
            (float) $this->points
        ));
        self::setWithReflection($reflector, 'disabled', $cupEvent, $this->disabled);
        self::setWithReflection($reflector, 'created', $cupEvent, self::reflectImpression($this->createdAt, $this->createdBy));
        self::setWithReflection($reflector, 'updated', $cupEvent, self::reflectImpression($this->updatedAt, $this->updatedBy));

        return $cupEvent;
    }

    /** @return string[] */
    public function uniqueIds(): array
    {
        return array_merge(parent::uniqueIds(), ['cupId', 'eventId']);
    }
}
