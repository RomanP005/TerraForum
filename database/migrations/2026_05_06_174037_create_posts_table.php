<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->longText('content')->comment('Содержание сообщения');
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('theme_id')
                ->constrained('themes')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('parent_post_id')
                ->nullable()
                ->constrained('posts')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('is_best_answer')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_edited')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['theme_id', 'is_best_answer']);
            $table->fullText('content');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
