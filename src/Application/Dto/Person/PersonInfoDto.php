<?php

declare(strict_types=1);

namespace App\Application\Dto\Person;

use App\Application\Dto\AbstractDto;

final class PersonInfoDto extends AbstractDto
{
    public string $firstname;

    public string $lastname;

    public ?string $yearOfBirthday = null;

    public static function validationRules(): array
    {
        return [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'yearOfBirthday' => 'digits:4|integer|min:1900',
        ];
    }

    public function fromArray(array $data): self
    {
        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];
        $this->yearOfBirthday = $data['yearOfBirthday'] ?? null;

        return $this;
    }
}
