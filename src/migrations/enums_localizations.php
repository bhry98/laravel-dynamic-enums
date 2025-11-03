<?php

use Bhry98\LaravelDynamicEnums\Models\{
    EnumsModel,
    EnumsLocalizationsModel
};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create((new EnumsLocalizationsModel())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('enum_id')->references('id')->on((new EnumsModel())->getTable())->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('key', 50);
            $table->string('lang', 5);
            $table->longText('value');
        });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists((new EnumsLocalizationsModel())->getTable());
        Schema::enableForeignKeyConstraints();
    }
};
