<?php

declare(strict_types=1);

namespace App\Application\Dto\Person;

use App\Application\Dto\Shared\ImpressionDto;

final class ViewPersonDto
{
    public string $id;

    public string $firstname;

    public string $lastname;

    public ?int $yearOfBirthday;

    public ?string $clubId;

    public ?string $activeRankId;

    /**
     * @var array<string, mixed>
     */
    public array $attributes;

    public ImpressionDto $created;

    public ImpressionDto $updated;
}
