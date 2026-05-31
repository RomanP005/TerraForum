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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content')->comment('Текст комментария / краткого отзыва');

            // Автор комментария
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()->cascadeOnUpdate();

            // Полиморфная связь — комментарий относится к новости / услуге / посту
            $table->morphs('commentable');

            // Древовидные ответы
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('comments')
                ->cascadeOnDelete()->cascadeOnUpdate();

            // Краткая оценка для отзывов на новости/услуги (1-5)
            // Голоса лайк/дизлайк на сам комментарий — через laravel-vote
            $table->unsignedTinyInteger('rating_value')->nullable()->comment('Оценка 1-5 для отзывов');

            $table->boolean('is_approved')->default(true);
            $table->boolean('is_edited')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
