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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 280)->unique();
            $table->text('excerpt')->nullable()->comment('Краткое описание');
            $table->longText('content');

            // Автор - администратор
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // Категория новости (агрономия, погода, рынок и т.д.)
            $table->string('news_category', 100)->nullable();

            // Публикация
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();

            // ПРИМЕЧАНИЕ:
            // - обложка/изображения — через spatie/laravel-medialibrary
            // - теги — через spatie/laravel-tags
            // - оценки — через overtrue/laravel-vote
            // - комментарии-отзывы — таблица comments (полиморфно)

            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('published_at');
            $table->index(['is_published', 'published_at']);
            $table->index('news_category');
            $table->fullText(['title', 'excerpt', 'content']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
