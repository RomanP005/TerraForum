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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 280)->unique();
            $table->longText('description');

            // Автор объявления
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Категория услуги (вспашка, обрезка, полив и т.д.)
            $table->string('service_category', 100);

            // Цена
            $table->decimal('price', 12, 2)->nullable();
            $table->string('price_unit', 50)->nullable()->comment('за час / за сотку / договорная');
            $table->boolean('price_negotiable')->default(false);

            // География
            $table->string('region', 100);
            $table->string('city', 100)->nullable();

            // Контакты
            $table->string('contact_phone', 30)->nullable();
            $table->string('contact_email', 150)->nullable();

            // ПРИМЕЧАНИЕ:
            // - изображения — через spatie/laravel-medialibrary
            // - теги — через spatie/laravel-tags
            // - оценки услуги (звёзды) — таблица comments (rating_value 1-5)
            // - лайки — через overtrue/laravel-vote

            // Статусы
            $table->boolean('is_active')->default(true);
            $table->boolean('is_approved')->default(true);
            $table->timestamp('expires_at')->nullable()->comment('Срок размещения');

            $table->unsignedInteger('views_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Индексы для фильтрации по региону + категории
            $table->index(['region', 'service_category']);
            $table->index('is_active');
            $table->fullText(['title', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
