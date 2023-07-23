<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent;

use App\Domain\AggregatedRoot;
use App\Domain\Shared\Footprint;
use App\Domain\Shared\Impression;
use App\Domain\User\UserId;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ReflectionClass;

/**
 * @property int $incrementalId
 * @property string $id
 * @property bool $disabled
 * @property string $createdAt
 * @property string $createdBy
 * @property string $updatedAt
 * @property string $updatedBy
 *
 * @method static self active()
 * @method self first()
 * @method int count()
 * @method static self whereId(string $id)
 * @method static self where(string $column, mixed $operator = null, mixed $value = null)
 * @method static self lockForUpdate()
 * @method static self limit(int $limit)
 * @method static self offset(int $offset)
 * @method static self orderByDesc(string ...$columns)
 * @method Collection|null get()
 */
abstract class AggregateModel extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'incrementalId';

    /** @var string[] */
    protected $casts = [
        'disabled' => 'boolean',
    ];

    public static function fromAggregate(AggregatedRoot $root): static
    {
        /** @phpstan-ignore-next-line */
        $model = new static();
        $model->id = $root->id()->toString();
        $model->disabled = $root->disabled();
        $model->createdAt = self::dateToString($root->created()->at);
        $model->createdBy = $root->created()->by->id->toString();
        $model->updatedAt = self::dateToString($root->updated()->at);
        $model->updatedBy = $root->updated()->by->id->toString();

        return $model;
    }

    protected static function dateToString(DateTimeImmutable $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected static function reflectImpression(string $at, string $by): Impression
    {
        return new Impression(new DateTimeImmutable($at), new Footprint(UserId::fromString($by)));
    }

    protected static function setWithReflection(
        ReflectionClass $reflector,
        string $name,
        AggregatedRoot $root,
        mixed $value
    ): void {
        $property = $reflector->getProperty($name);
        $property->setValue($root, $value);
    }

    abstract public function toAggregate(): AggregatedRoot;

    /** @return string[] */
    public function uniqueIds(): array
    {
        return ['id', 'createdBy', 'updatedBy'];
    }

    public function scopeActive(self|Builder $query): self|Builder
    {
        return $query->where('disabled', false);
    }
}
