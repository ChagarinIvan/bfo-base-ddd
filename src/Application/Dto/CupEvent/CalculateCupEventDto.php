<?php

declare(strict_types=1);

namespace App\Application\Dto\CupEvent;

use App\Application\Dto\AbstractDto;
use OpenApi\Annotations as OpenApi;

/** @OpenApi\Schema(additionalProperties=false, required={"id", "group"}) */
final class CalculateCupEventDto extends AbstractDto
{
    public string $id;

    public string $group;

    public static function validationRules(): array
    {
        return [
            'id' => 'required|uuid',
            'group' => 'required|regex:/^(M|W)_\d\d$/',
        ];
    }

    public function fromArray(array $data): self
    {
        $this->id = $data['id'];
        $this->group = $data['group'];

        return $this;
    }
}
