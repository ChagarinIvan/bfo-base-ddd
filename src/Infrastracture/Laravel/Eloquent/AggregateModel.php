<?php

declare(strict_types=1);

namespace App\Infrastracture\Laravel\Eloquent;

use App\Domain\AggregatedRoot;
use App\Domain\Shared\Footprint;
use App\Domain\Shared\Impression;
use App\Domain\User\UserId;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

/**
 * @property int    $incrementalId
 * @property string $id
 * @property string $createdAt
 * @property string $createdBy
 * @property string $updatedAt
 * @property string $updatedBy
 */
abstract class AggregateModel extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'incrementalId';

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

    abstract public static function fromAggregate(AggregatedRoot $root): self;

    abstract public function toAggregate(): AggregatedRoot;

    /** @return string[] */
    public function uniqueIds(): array
    {
        return ['id', 'createdBy', 'updatedBy'];
    }
}
