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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use ReflectionClass;
use function array_merge;

/**
 * @property string $name
 * @property string $competitionId
 * @property string $description
 * @property string $date
 * @property bool $disabled
 *
 * @method static self whereId(string $id)
 * @method static self active()
 * @method static self where(string $column, mixed $operator = null, mixed $value = null)
 * @method static self lockForUpdate()
 * @method static self first()
 * @method static self limit(int $limit)
 * @method static self offset(int $offset)
 * @method static self orderByDesc(string ...$columns)
 * @method int count()
 * @method Collection|null get()
 */
class EventModel extends AggregateModel
{
    protected $table = 'ddd_event';

    /** @var string[] */
    protected $casts = [
        'disabled' => 'boolean',
    ];

    /**
     * @param Event $root
     */
    public static function fromAggregate(AggregatedRoot $root): self
    {
        $model = new self();
        $model->id = $root->id()->toString();
        $model->competitionId = $root->competitionId()->toString();
        $model->name = $root->name();
        $model->description = $root->description();
        $model->date = self::dateToString($root->date());
        $model->disabled = $root->disabled();
        $model->createdAt = self::dateToString($root->created()->at);
        $model->createdBy = $root->created()->by->id->toString();
        $model->updatedAt = self::dateToString($root->updated()->at);
        $model->updatedBy = $root->updated()->by->id->toString();

        return $model;
    }

    /** @return string[] */
    public function uniqueIds(): array
    {
        return array_merge(parent::uniqueIds(), ['competitionId']);
    }

    public function scopeActive(self|Builder $query): self|Builder
    {
        return $query->where('disabled', false);
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
