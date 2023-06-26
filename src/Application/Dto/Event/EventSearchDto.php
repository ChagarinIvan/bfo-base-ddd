<?php

declare(strict_types=1);

namespace App\Application\Dto\Event;

use App\Application\Dto\AbstractDto;
use App\Application\Dto\Shared\Pagination;
use function array_key_exists;
use function array_merge;
use function get_object_vars;

final class EventSearchDto extends AbstractDto
{
    public Pagination $pagination;

    public ?string $competitionId;

    public ?string $name;

    public ?string $description;

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
        $this->setParam('competitionId', $data);
        $this->setParam('name', $data);
        $this->setParam('description', $data);
        $this->setParam('date', $data);

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

    /**
     * @param array<string, mixed> $data
     */
    private function setParam(string $name, array $data): void
    {
        if (array_key_exists($name, $data)) {
            $this->$name = $data[$name];
        }
    }
}
