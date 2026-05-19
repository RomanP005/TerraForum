<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->comment('Название категории');
            $table->string('slug', 160)->unique()->comment('URL-идентификатор (генерируется spatie/laravel-sluggable)');
            $table->text('description')->nullable()->comment('Описание категории');
            $table->string('icon', 100)->nullable()->comment('Иконка категории');
            $table->string('color', 20)->nullable()->comment('Цветовая метка');

            // Иерархия (подкатегории)
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['parent_id', 'sort_order']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
