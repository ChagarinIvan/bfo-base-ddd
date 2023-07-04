<?php

declare(strict_types=1);

namespace App\Application\Dto\Club;

use App\Application\Dto\AbstractDto;
use App\Application\Dto\Shared\Pagination;
use function array_merge;
use function get_object_vars;

final class ClubSearchDto extends AbstractDto
{
    public Pagination $pagination;

    public ?string $name;

    public static function validationRules(): array
    {
        return [
            ...Pagination::validationRules(),
            'name' => '',
        ];
    }

    public function fromArray(array $data): AbstractDto
    {
        $this->pagination = new Pagination();
        $this->pagination = $this->pagination->fromArray($data);
        $this->setParam('name', $data);

        return $this;
    }

    /**
     * @return array<string, string|int>
     */
    public function toArray(): array
    {
        $result = get_object_vars($this);
        unset($result['pagination']);

        return array_merge($result, get_object_vars($this->pagination));
    }
}
