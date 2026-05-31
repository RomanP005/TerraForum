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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->longText('content')->comment('Содержание сообщения');

            // Автор сообщения
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()->cascadeOnUpdate();

            // Тема, к которой относится сообщение
            $table->foreignId('theme_id')
                ->constrained('themes')
                ->cascadeOnDelete()->cascadeOnUpdate();

            // Ответ на конкретное сообщение (древовидная структура)
            $table->foreignId('parent_post_id')
                ->nullable()
                ->constrained('posts')
                ->cascadeOnDelete()->cascadeOnUpdate();

            // ПРИМЕЧАНИЕ:
            // - вложения (фото) — через spatie/laravel-medialibrary
            // - голоса (лайки/дизлайки) — через overtrue/laravel-vote

            // Маркировка лучшего ответа (для умного поиска и ранжирования)
            $table->boolean('is_best_answer')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_edited')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['theme_id', 'is_best_answer']);
            $table->fullText('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
