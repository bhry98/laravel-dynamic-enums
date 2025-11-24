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
        Schema::create((new EnumsModel())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string("code", 50)->unique()->index();
            $table->string("group", 100)->index();
            $table->string('default_name');
            $table->longText('default_description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->default('#808080');
            $table->integer('ordering')->default(1);
            $table->boolean('active')->default(true);
            $table->foreignId('parent_id')->nullable()->references('id')->on((new EnumsModel)->getTable())->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists((new EnumsModel())->getTable());
        Schema::enableForeignKeyConstraints();
    }
};
