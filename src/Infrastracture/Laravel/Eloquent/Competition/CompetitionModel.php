<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Competition;

use App\Domain\AggregatedRoot;
use App\Domain\Competition\Competition;
use App\Domain\Competition\CompetitionId;
use App\Domain\Competition\CompetitionInfo;
use App\Infrastracture\Laravel\Eloquent\AggregateModel;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use ReflectionClass;

/**
 * @property string $name
 * @property string $description
 * @property string $from
 * @property string $to
 * @property bool $disabled
 *
 * @method static self whereId(string $id)
 * @method static self active()
 * @method static self where(string $column, mixed $value)
 * @method static self lockForUpdate()
 * @method static self first()
 * @method static self limit(int $limit)
 * @method static self offset(int $offset)
 * @method Collection|null get()
 */
class CompetitionModel extends AggregateModel
{
    protected $table = 'ddd_competition';

    /** @var string[] */
    protected $casts = [
        'disabled' => 'boolean',
    ];

    /**
     * @param Competition $root
     */
    public static function fromAggregate(AggregatedRoot $root): self
    {
        $model = new self();
        $model->id = $root->id()->toString();
        $model->name = $root->name();
        $model->description = $root->description();
        $model->from = self::dateToString($root->from());
        $model->to = self::dateToString($root->to());
        $model->disabled = $root->disabled();
        $model->createdAt = self::dateToString($root->created()->at);
        $model->createdBy = $root->created()->by->id->toString();
        $model->updatedAt = self::dateToString($root->updated()->at);
        $model->updatedBy = $root->updated()->by->id->toString();

        return $model;
    }

    public function scopeActive(self|Builder $query): self|Builder
    {
        return $query->where('disabled', false);
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
