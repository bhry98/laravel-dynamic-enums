<?php

namespace Bhry98\LaravelDynamicEnums\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EnumsLocalizationsModel extends Model
{
    protected $table = 'enums_localizations';
    protected $fillable = [
        'id',
        'enum_id',
        'key',
        'lang',
        'value',
    ];

    public function enum(): HasOne
    {
        return $this->hasOne(EnumsModel::class, 'id', 'enum_id');
    }
}
