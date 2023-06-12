<?php

declare(strict_types=1);

namespace App\Application\Dto\Event;

use App\Application\Dto\AbstractDto;
use OpenApi\Annotations as OpenApi;

/**
 * @OpenApi\Schema(
 *   additionalProperties=false,
 *   required={
 *     "name",
 *     "description",
 *     "date",
 *   })
 */
final class EventInfoDto extends AbstractDto
{
    public string $name;

    public string $description;

    /** @OpenApi\Property(type="date-time") */
    public string $date;

    public static function validationRules(): array
    {
        return [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'date' => 'required|date',
        ];
    }

    public function fromArray(array $data): self
    {
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->date = $data['date'];

        return $this;
    }
}
