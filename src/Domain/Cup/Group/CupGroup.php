<?php

declare(strict_types=1);

namespace App\Domain\Cup\Group;

use RuntimeException;
use function preg_match;

final readonly class CupGroup
{
    public static function create(GroupMale $male, GroupAge $age): self
    {
        return new self($male, $age);
    }

    public function fromString(string $value): self
    {
        if (preg_match('#(\D)_(\d+)#', $value, $m)) {
            return new self(GroupMale::from($m[1]), GroupAge::from((int) $m[2]));
        }

        throw new RuntimeException('Wrong group');
    }

    public function toString(): string
    {
        return "{$this->male->value}_" . $this->age->value;
    }

    public function next(): self
    {
        return new self($this->male, $this->age->next());
    }

    public function prev(): self
    {
        return new self($this->male, $this->age->prev());
    }

    public function equal(self $other): bool
    {
        return $this->toString() === $other->toString();
    }

    private function __construct(
        public GroupMale $male,
        public GroupAge $age,
    ) {
    }
}
