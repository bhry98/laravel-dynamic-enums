<?php

namespace Bhry98\LaravelDynamicEnums\Services;

use BackedEnum;
use Bhry98\LaravelDynamicEnums\Models\EnumsLocalizationsModel;
use Bhry98\LaravelDynamicEnums\Models\EnumsModel;
use Exception;

class DynamicEnums
{
    public string|BackedEnum|null $enumClass;
    protected static array $cache = [];

    public function __construct()
    {
        $this->enumClass = config('bhry98-dynamic-enums.enum_class', \Bhry98\LaravelDynamicEnums\Enums\DynamicEnumTypes::class);
    }

    /**
     * @throws Exception
     */
    public function create(
        string  $group,
        string  $defaultName,
        ?string $defaultDescription = null,
        ?string $icon = null,
        ?string $color = null,
        ?int    $ordering = 1,
        ?int    $parent_id = null,
        ?array  $nameLocales = [],
        ?array  $descriptionLocales = [],
    ): EnumsModel
    {
        try {
            $enumRecord = EnumsModel::query()
                ->create([
                    'group' => $group,
                    'default_name' => $defaultName,
                    'default_description' => $defaultDescription,
                    'icon' => $icon,
                    'color' => $color ?? config('bhry98-dynamic-enums.enum_color', "#808080"),
                    'ordering' => $ordering,
                    'parent_id' => $parent_id,
                ]);
            if (!$enumRecord) throw new Exception('Cant Create Dynamic Enum Record');
            if ($nameLocales) {
                foreach ($nameLocales as $nameLangKey => $nameValue) {
                    EnumsLocalizationsModel::query()->create([
                        'enum_id' => $enumRecord->id,
                        'key' => "name",
                        'lang' => $nameLangKey,
                        'value' => $nameValue,
                    ]);
                }
            }
            if ($descriptionLocales) {
                foreach ($descriptionLocales as $descriptionLangKey => $descriptionValue) {
                    EnumsLocalizationsModel::query()->create([
                        'enum_id' => $enumRecord->id,
                        'key' => "description",
                        'lang' => $descriptionLangKey,
                        'value' => $descriptionValue,
                    ]);
                }
            }
            return $enumRecord->refresh();
        } catch (\Exception $exception) {
            dd($exception);
            throw new $exception;
        }
    }

    public function get(string $group, ?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();

        return self::$cache[$group][$locale] ??= EnumsModel::query()
            ->where('group', $group)
            ->with(['locales'])
            ->orderBy('ordering')
            ->get()
            ->map(fn($item) => [
                'code' => $item->code,
                'name' => $item->locales?->value ?? $item->default_name,
                'color' => $item->color,
                'icon' => $item->icon,
            ])
            ->toArray();
    }

    public function clearCache(string $group = null): void
    {
        if ($group) unset(self::$cache[$group]);
        else self::$cache = [];
    }
}