<?php

declare(strict_types=1);

namespace App\Application\Dto\Person;

use App\Application\Dto\AbstractDto;
use App\Application\Dto\Event\EventInfoDto;

final class PersonDto extends AbstractDto
{
    public PersonInfoDto $info;

    /**
     * @var array<string, mixed>
     */
    public array $attributes = [];

    public ?string $clubId = null;

    public static function validationRules(): array
    {
        return [
            ...EventInfoDto::validationRules(),
            'clubId' => 'uuid',
        ];
    }

    public function fromArray(array $data): self
    {
        $this->info = new PersonInfoDto();
        $this->info = $this->info->fromArray($data);
        $this->attributes = $data['attributes'] ?? [];
        $this->clubId = $data['clubId'] ?? null;

        return $this;
    }
}
