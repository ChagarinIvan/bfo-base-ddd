<?php

declare(strict_types=1);

namespace App\Application\Dto\Shared;

use App\Application\Dto\AbstractDto;

class Pagination extends AbstractDto
{
    public int $page = 1;

    public int $perPage = 20;

    public static function validationRules(): array
    {
        return [
            'page' => 'int|min:1',
            'perPage' => 'int|min:1',
        ];
    }

    public function fromArray(array $data): self
    {
        $this->page = (int) ($data['page'] ?? $this->page);
        $this->perPage = (int) ($data['perPage'] ?? $this->perPage);

        return $this;
    }
}
