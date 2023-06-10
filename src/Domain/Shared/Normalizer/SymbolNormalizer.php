<?php

declare(strict_types=1);

namespace App\Domain\Shared\Normalizer;

use function str_replace;

final readonly class SymbolNormalizer implements Normalizer
{
    // ru symbols => en symbols or alternative
    private const SYMBOL_MAP = [
        'с' => ['c'],
        'а' => ['a'],
        'о' => ['o'],
        'у' => ['y'],
        'р' => ['p'],
        'х' => ['x'],
        'е' => ['e', 'ё'],
    ];

    public function normalize(string $line): string
    {
        // Исправляем символы
        foreach (self::SYMBOL_MAP as $symbol => $analogs) {
            $line = str_replace($analogs, $symbol, $line);
        }

        return $line;
    }
}
