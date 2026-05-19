<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * ПРИМЕЧАНИЕ: можно использовать стандартную миграцию Laravel
     * `php artisan notifications:table` — она создаёт таблицу с UUID и data/JSON.
     * Здесь — своя таблица с кастомными полями для удобства фильтрации в UI.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Получатель
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Тип события: new_reply, mention, vote, moderation_warning,
            //              new_news, new_service, best_answer
            $table->string('type', 50)->comment('Тип события');

            $table->string('title', 255);
            $table->text('message');

            // Полиморфная ссылка на объект
            $table->nullableMorphs('notifiable');

            // URL для перехода
            $table->string('url', 500)->nullable();

            // Прочтение
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            // Канал доставки
            $table->boolean('sent_via_email')->default(false);

            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
