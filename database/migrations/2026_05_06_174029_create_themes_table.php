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
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->comment('Заголовок темы');
            $table->string('slug', 280)->unique();
            $table->longText('content')->comment('Тело первого сообщения');

            // Автор темы
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()->cascadeOnUpdate();

            // Категория темы
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete()->cascadeOnUpdate();

            // Статусы темы
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->boolean('is_approved')->default(true);

            // Метрики
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);

            // ПРИМЕЧАНИЕ:
            // - голоса/рейтинг — через overtrue/laravel-vote (трейт Voteable)
            // - теги — через spatie/laravel-tags (трейт HasTags, таблица taggables)
            // - изображения/вложения — через spatie/laravel-medialibrary (трейт InteractsWithMedia)

            // Активность для сортировки в списках
            $table->timestamp('last_activity_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Индексы
            $table->index('last_activity_at');
            $table->index('views_count');
            $table->index(['category_id', 'is_pinned', 'last_activity_at']);

            // Полнотекстовый индекс для Laravel Scout с #[SearchUsingFullText]
            $table->fullText(['title', 'content']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
