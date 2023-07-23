<?php

declare(strict_types=1);

namespace App\Application\Dto\CupEvent;

use App\Application\Dto\AbstractDto;

final class CupEventDto extends AbstractDto
{
    public string $cupId;

    public string $eventId;

    public CupEventAttributesDto $attributes;

    /** @return array<string, mixed> */
    public static function validationRules(): array
    {
        return [
            ...CupEventAttributesDto::validationRules(),
            'cupId' => 'required|uuid',
            'eventId' => 'required|uuid',
        ];
    }

    public function fromArray(array $data): self
    {
        $this->setStringParam('cupId', $data);
        $this->setStringParam('eventId', $data);
        $attributesDto = new CupEventAttributesDto();
        $this->attributes = $attributesDto->fromArray($data);

        return $this;
    }
}
