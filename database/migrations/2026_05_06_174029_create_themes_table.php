<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->comment('Заголовок темы');
            $table->string('slug', 280)->unique();
            $table->longText('content')->comment('Тело первого сообщения');
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('last_activity_at');
            $table->index('views_count');
            $table->index(['category_id', 'is_pinned', 'last_activity_at']);
            $table->fullText(['title', 'content']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
