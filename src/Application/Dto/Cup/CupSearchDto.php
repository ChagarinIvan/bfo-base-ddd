<?php

declare(strict_types=1);

namespace App\Application\Dto\Cup;

use App\Application\Dto\AbstractDto;
use App\Application\Dto\Shared\Pagination;
use App\Domain\Cup\CupType;
use Illuminate\Validation\Rules\Enum;
use function array_merge;
use function get_object_vars;

final class CupSearchDto extends AbstractDto
{
    public Pagination $pagination;

    // like match
    public ?string $name;

    // exact match
    public ?string $year;

    // exact match
    public ?string $type;

    public static function validationRules(): array
    {
        return [
            ...Pagination::validationRules(),
            'name' => '',
            'year' => 'integer|digits:4|min:1900|',
            'type' => [new Enum(CupType::class)],
        ];
    }

    public function fromArray(array $data): AbstractDto
    {
        $this->pagination = new Pagination();
        $this->pagination = $this->pagination->fromArray($data);
        $this->setStringParam('name', $data);
        $this->setStringParam('year', $data);
        $this->setStringParam('type', $data);

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
