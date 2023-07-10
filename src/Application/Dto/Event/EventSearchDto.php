<?php

declare(strict_types=1);

namespace App\Application\Dto\Event;

use App\Application\Dto\AbstractDto;
use App\Application\Dto\Shared\Pagination;
use function array_merge;
use function get_object_vars;

final class EventSearchDto extends AbstractDto
{
    public Pagination $pagination;

    // exact match
    public ?string $competitionId;

    // like match
    public ?string $name;

    // like match
    public ?string $description;

    // exact match
    public ?string $date;

    public static function validationRules(): array
    {
        return [
            ...Pagination::validationRules(),
            'competitionId' => 'uuid',
            'name' => '',
            'description;' => '',
            'date' => 'date',
        ];
    }

    public function fromArray(array $data): AbstractDto
    {
        $this->pagination = new Pagination();
        $this->pagination = $this->pagination->fromArray($data);
        $this->setStringParam('competitionId', $data);
        $this->setStringParam('name', $data);
        $this->setStringParam('description', $data);
        $this->setStringParam('date', $data);

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
