<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Event;

use App\Domain\AggregatedRoot;
use App\Domain\Competition\CompetitionId;
use App\Domain\Event\Event;
use App\Domain\Event\EventId;
use App\Domain\Event\EventInfo;
use App\Infrastracture\Laravel\Eloquent\AggregateModel;
use DateTimeImmutable;
use ReflectionClass;
use function array_merge;

/**
 * @property string $name
 * @property string $competitionId
 * @property string $description
 * @property string $date
 */
class EventModel extends AggregateModel
{
    protected $table = 'ddd_event';

    /**
     * @param Event $root
     */
    public static function fromAggregate(AggregatedRoot $root): static
    {
        $model = parent::fromAggregate($root);
        $model->competitionId = $root->competitionId()->toString();
        $model->name = $root->name();
        $model->description = $root->description();
        $model->date = self::dateToString($root->date());

        return $model;
    }

    /** @return string[] */
    public function uniqueIds(): array
    {
        return array_merge(parent::uniqueIds(), ['competitionId']);
    }

    public function toAggregate(): AggregatedRoot
    {
        $reflector = new ReflectionClass(Event::class);
        $event = $reflector->newInstanceWithoutConstructor();
        $reflector = new ReflectionClass($event);
        self::setWithReflection($reflector, 'id', $event, EventId::fromString($this->id));
        self::setWithReflection($reflector, 'competitionId', $event, CompetitionId::fromString($this->competitionId));
        self::setWithReflection($reflector, 'info', $event, new EventInfo(
            $this->name,
            $this->description,
            new DateTimeImmutable($this->date),
        ));
        self::setWithReflection($reflector, 'disabled', $event, $this->disabled);
        self::setWithReflection($reflector, 'created', $event, self::reflectImpression($this->createdAt, $this->createdBy));
        self::setWithReflection($reflector, 'updated', $event, self::reflectImpression($this->updatedAt, $this->updatedBy));

        return $event;
    }
}
