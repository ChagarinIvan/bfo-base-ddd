<?php

declare(strict_types=1);

namespace App\Application\Dto\Person;

use App\Application\Dto\Shared\ImpressionDto;

/**
 * @OpenApi\Schema(
 *   additionalProperties=false,
 *   required={
 *     "id",
 *     "firstname",
 *     "lastname",
 *     "yearOfBirthday",
 *     "clubId",
 *     "activeRankId",
 *     "attributes",
 *     "created",
 *     "updated"
 *   })
 */
final class ViewPersonDto
{
    /** @OpenApi\Property(type="uuid") */
    public string $id;

    public string $firstname;

    public string $lastname;

    /** @OpenApi\Property(type="number") */
    public ?int $yearOfBirthday;

    /** @OpenApi\Property(type="uuid", nullable="true") */
    public ?string $clubId;

    /** @OpenApi\Property(type="uuid", nullable="true") */
    public ?string $activeRankId;

    /**
     * @OpenApi\Property(type="object")
     *
     * @var array<string, mixed>
     */
    public array $attributes;

    public ImpressionDto $created;

    public ImpressionDto $updated;
}
