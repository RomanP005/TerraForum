<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('service_category')->nullable();

            // Цена
            $table->decimal('price', 10, 2)->nullable();
            $table->string('price_unit')->default('руб.')->nullable(); // руб/час, руб/га и т.д.
            $table->boolean('price_negotiable')->default(false);

            // Контакты
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();

            // Модерация
            $table->boolean('is_approved')->default(false); // false = на модерации
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['region', 'service_category']);
            $table->index('is_approved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
