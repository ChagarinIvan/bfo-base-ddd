<?php

declare(strict_types=1);

namespace App\Application\Dto\Competition;

use App\Application\Dto\AbstractDto;
use OpenApi\Annotations as OpenApi;

/**
 * @OpenApi\Schema(
 *   additionalProperties=false,
 *   required={
 *     "name",
 *     "description",
 *     "from",
 *     "to",
 *   })
 */
final class CompetitionInfoDto extends AbstractDto
{
    public string $name;

    public string $description;

    /** @OpenApi\Property(type="date-time") */
    public string $from;

    /** @OpenApi\Property(type="date-time") */
    public string $to;

    public static function validationRules(): array
    {
        return [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'from' => 'required|date',
            'to' => 'required|date',
        ];
    }

    public function fromArray(array $data): self
    {
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->from = $data['from'];
        $this->to = $data['to'];

        return $this;
    }
}
