<?php

declare(strict_types=1);

namespace App\Application\Dto\Cup;

/**
 * @OpenApi\Schema(
 *   additionalProperties=false,
 *   required={
 *     "type",
 *     "groups",
 *   })
 */
final class ViewCupTypeDefinitionDto
{
    public string $type;

    /** @var array<int, string> */
    public array $groups;
}
