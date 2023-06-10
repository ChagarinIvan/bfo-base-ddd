<?php

declare(strict_types=1);

namespace App\Domain\Shared\Normalizer;

use function in_array;

final readonly class FirstnameNormalizer implements Normalizer
{
    /**
     * карта исправления имён, разные сокращения и формы аналоги.
     */
    private const EDIT_MAP = [
        'дмитрий' => ['дима'],
        'павел' => ['паша'],
        'мария' => ['маша'],
        'иван' => ['ваня'],
        'татьяна' => ['таня'],
        'анастасия' => ['настя'],
        'екатерина' => ['катя'],
        'юрий' => ['юра'],
        'ольга' => ['оля'],
        'валентина' => ['валя'],
        'александр' => ['саша'],
        'алексей' => ['леша'],
        'светлана' => ['света'],
        'владислав' => ['влад'],
        'вячеслав' => ['слава'],
        'наталья' => ['наташа'],
        'михаил' => ['миша'],
        'анна' => ['аня'],
        'елена' => ['лена'],
    ];

    public function normalize(string $line): string
    {
        // Заменяем формы имён
        foreach (self::EDIT_MAP as $name => $analogs) {
            if (in_array($line, $analogs, true)) {
                return $name;
            }
        }

        return $line;
    }
}
