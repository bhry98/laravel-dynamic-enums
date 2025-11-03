<?php

namespace Bhry98\LaravelDynamicEnums\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EnumsModel extends Model
{
    use SoftDeletes;

    protected $table = 'enums';
    protected $fillable = [
        'id',
        'code',
        'group',
        'default_name',
        'default_description',
        'icon',
        'color',
        'ordering',
        'parent_id',
    ];
    protected $casts = [
        'ordering' => 'integer',
    ];

    public function name(): HasOne
    {
        return $this->hasOne(EnumsLocalizationsModel::class, 'enum_id', 'id')
            ->where([
                'lang' => app()->getLocale(),
                "key" => "name"
            ]);
    }

    public function parent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'parent_id')
            ->with(["name", "description"]);
    }

    public function description(): HasOne
    {
        return $this->hasOne(EnumsLocalizationsModel::class, 'enum_id', 'id')
            ->where([
                'lang' => app()->getLocale(),
                "key" => "description"
            ]);
    }

    protected static function booting(): void
    {
        self::creating(function ($model) {
            $model->code = self::createUniqueCode();
        });
//        static::addGlobalScope('locales', function (Builder $builder) {
//            $builder->with(["name", "description"]);
//        });
    }

//    protected function locales(Builder $query): void
//    {
//        $query->with(["name", "description"]);
//    }

    private static function createUniqueCode(): string
    {
        $length = (int)config("bhry98-dynamic-enums.length", 10);
        $code = Str::random($length);
        if (self::query()->where('code', $code)->exists()) {
            return self::createUniqueCode();
        }
        return $code;
    }
}
