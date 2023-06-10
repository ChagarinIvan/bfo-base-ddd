<?php

declare(strict_types=1);

namespace App\Application\Dto\Person;

use OpenApi\Annotations as OpenApi;

/** @OpenApi\Schema(
 *   additionalProperties=false,
 *   required={
 *     "firstname",
 *     "lastname",
 *   }
 * )
 */
final class PersonDto
{
    public string $firstname;

    public string $lastname;

    /** @OpenApi\Property(type="number") */
    public ?string $yearOfBirthday = null;

    /**
     * @OpenApi\Property(type="object")
     *
     * @var array<string, mixed>
     */
    public array $attributes = [];

    /** @OpenApi\Property(type="uuid", nullable="true") */
    public ?string $clubId = null;
}
