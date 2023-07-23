<?php

declare(strict_types=1);

namespace App\Application\Dto\CupEvent;

use App\Application\Dto\AbstractDto;

final class GroupDistancesDto extends AbstractDto
{
    public string $groupId;

    /** @var string[] */
    public array $distances;

    /** @return array<string, mixed> */
    public static function validationRules(): array
    {
        return [
            'group_id' => 'required|group_id',
            'distances' => 'required|array|uuid',
        ];
    }

    public function fromArray(array $data): self
    {
        $this->groupId = $data['group_id'];
        $this->distances = (array) $data['distances'];

        return $this;
    }
}
