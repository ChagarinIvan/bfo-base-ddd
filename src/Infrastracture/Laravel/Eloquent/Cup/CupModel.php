<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Cup;

use App\Domain\AggregatedRoot;
use App\Domain\Cup\Cup;
use App\Domain\Cup\CupId;
use App\Domain\Cup\CupType;
use App\Infrastracture\Laravel\Eloquent\AggregateModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use ReflectionClass;

/**
 * @property string $name
 * @property int $eventsCount
 * @property int $year
 * @property string $type
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
class CupModel extends AggregateModel
{
    protected $table = 'ddd_cup';

    /** @var string[] */
    protected $casts = [
        'disabled' => 'boolean',
    ];

    /**
     * @param Cup $root
     */
    public static function fromAggregate(AggregatedRoot $root): self
    {
        $model = new self();
        $model->id = $root->id()->toString();
        $model->name = $root->name();
        $model->eventsCount = $root->eventsCount();
        $model->year = $root->year();
        $model->type = $root->type()->toString();
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
        $reflector = new ReflectionClass(Cup::class);
        $cup = $reflector->newInstanceWithoutConstructor();
        $reflector = new ReflectionClass($cup);
        self::setWithReflection($reflector, 'id', $cup, CupId::fromString($this->id));
        self::setWithReflection($reflector, 'name', $cup, $this->name);
        self::setWithReflection($reflector, 'eventsCount', $cup, $this->eventsCount);
        self::setWithReflection($reflector, 'year', $cup, $this->year);
        self::setWithReflection($reflector, 'type', $cup, CupType::from($this->type));
        self::setWithReflection($reflector, 'disabled', $cup, $this->disabled);
        self::setWithReflection($reflector, 'created', $cup, self::reflectImpression($this->createdAt, $this->createdBy));
        self::setWithReflection($reflector, 'updated', $cup, self::reflectImpression($this->updatedAt, $this->updatedBy));

        return $cup;
    }
}
