<?php

namespace Bhry98\LaravelDynamicEnums\Facades;

use Bhry98\LaravelDynamicEnums\Models\EnumsModel;
use Illuminate\Support\Facades\Facade;

/**
 * @method static EnumsModel create(string $group, string $defaultName, ?string $defaultDescription = null, ?string $icon = null, ?string $color = null, ?int $ordering = 1, ?int $parent_id = null, ?array $nameLocales = [], ?array $descriptionLocales = [])
 * @method static array get(string $group, ?string $locale = null)
 * @method static void clearCache(?string $group = null)
 */
class DynamicEnums extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Bhry98\LaravelDynamicEnums\Services\DynamicEnums::class;
    }
}
