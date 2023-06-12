<?php

declare(strict_types=1);

namespace App\Application\Dto\Competition;

use App\Application\Dto\AbstractDto;
use OpenApi\Annotations as OpenApi;

/**
 * @OpenApi\Schema(
 *   additionalProperties=false
 *   required={
 *     "page",
 *     "perPage",
 *   })
 */
final class CompetitionSearchDto extends AbstractDto
{
    public int $page = 0;

    public int $perPage = 20;

    public static function validationRules(): array
    {
        return [
            'page' => 'int|min:1',
            'perPage' => 'int|min:10',
        ];
    }

    public function fromArray(array $data): self
    {
        $this->page = (int) ($data['page'] ?? $this->page);
        $this->perPage = (int) ($data['page'] ?? $this->perPage);

        return $this;
    }
}
