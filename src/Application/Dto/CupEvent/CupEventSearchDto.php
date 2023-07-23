<?php

declare(strict_types=1);

namespace App\Application\Dto\CupEvent;

use App\Application\Dto\AbstractDto;
use App\Application\Dto\Shared\Pagination;
use function array_merge;
use function get_object_vars;

final class CupEventSearchDto extends AbstractDto
{
    public Pagination $pagination;

    // exact match
    public ?string $cupId;

    // exact match
    public ?string $eventId;

    public static function validationRules(): array
    {
        return [
            ...Pagination::validationRules(),
            'cupId' => 'uuid',
            'eventId' => 'uuid',
        ];
    }

    public function fromArray(array $data): AbstractDto
    {
        $this->pagination = new Pagination();
        $this->pagination = $this->pagination->fromArray($data);
        $this->setStringParam('cupId', $data);
        $this->setStringParam('eventId', $data);

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
