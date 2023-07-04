<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent\Club;

use App\Domain\AggregatedRoot;
use App\Domain\Club\Club;
use App\Domain\Club\ClubId;
use App\Infrastracture\Laravel\Eloquent\AggregateModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use ReflectionClass;

/**
 * @property string $name
 * @property bool $disabled
 *
 * @method static self whereId(string $id)
 * @method static self active()
 * @method static self where(string $column, mixed $operator = null, mixed $value = null)
 * @method static self lockForUpdate()
 * @method self first()
 * @method static self limit(int $limit)
 * @method static self offset(int $offset)
 * @method static self orderByDesc(string ...$columns)
 * @method int count()
 * @method Collection|null get()
 */
class ClubModel extends AggregateModel
{
    protected $table = 'ddd_club';

    /** @var string[] */
    protected $casts = [
        'disabled' => 'boolean',
    ];

    /**
     * @param Club $root
     */
    public static function fromAggregate(AggregatedRoot $root): self
    {
        $model = new self();
        $model->id = $root->id()->toString();
        $model->name = $root->name();
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
