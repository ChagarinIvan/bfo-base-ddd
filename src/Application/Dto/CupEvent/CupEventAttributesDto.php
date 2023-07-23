<?php

declare(strict_types=1);

namespace App\Application\Dto\CupEvent;

use App\Application\Dto\AbstractDto;
use function array_map;

final class CupEventAttributesDto extends AbstractDto
{
    /** @var GroupDistancesDto[] */
    public array $groupsDistances;

    public float $points;

    /** @return array<string, mixed> */
    public static function validationRules(): array
    {
        return [
            'groupsDistances' => 'required|array',
            'groupsDistances.*.group_id' => 'required|group_id',
            'groupsDistances.*.distances' => 'required|array',
            'groupsDistances.*.distances.*' => 'uuid',
            'points' => 'required|numeric|min:0|',
        ];
    }

    public function fromArray(array $data): self
    {
        $this->groupsDistances = array_map(
            static fn (array $dtoData): GroupDistancesDto => (new GroupDistancesDto())->fromArray($dtoData),
            $data['groupsDistances'] ?? []
        );
        $this->points = (float) $data['points'];

        return $this;
    }
}
