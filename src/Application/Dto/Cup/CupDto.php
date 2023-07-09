<?php

declare(strict_types=1);

namespace App\Application\Dto\Cup;

use App\Application\Dto\AbstractDto;
use App\Domain\Cup\CupType;
use Illuminate\Validation\Rules\Enum;

final class CupDto extends AbstractDto
{
    public string $name;
    public int $eventCounts;
    public int $year;
    public string $type;

    /** @return array<string, mixed> */
    public static function validationRules(): array
    {
        return [
            'name' => 'required|max:255',
            'eventsCount' => 'required|integer|min:1',
            'year' => 'required|integer|digits:4|min:1900|',
            'type' => [
                'required',
                new Enum(CupType::class),
            ],
        ];
    }

    public function fromArray(array $data): self
    {
        $this->name = $data['name'];
        $this->eventCounts = (int) $data['eventsCount'];
        $this->year = (int) $data['year'];
        $this->type = $data['type'];

        return $this;
    }
}
