<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Provider\Cup\Group;

use App\Domain\Cup\Group\CupGroup;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

final class GroupIdValidationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Validator::extend('group_id', static function (string $attribute, string $value): bool {
            try {
                CupGroup::fromString($value);
            } catch (RuntimeException) {
                return false;
            }

            return true;
        });
    }
}
