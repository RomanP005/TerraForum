<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('moderation_logs', function (Blueprint $table) {
            $table->id();

            // Кто совершил действие
            $table->foreignId('moderator_id')->cascadeOnDelete()->cascadeOnUpdate()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Тип действия: warn_user, delete_post, delete_theme, ban_user,
            //               approve_content, edit_post, pin_theme, close_theme,
            //               restore_content, change_role
            $table->string('action_type', 50);

            // Объект воздействия (пользователь, тема, пост, комментарий, услуга, новость)
            $table->nullableMorphs('target');

            // Затронутый пользователь (для предупреждения / бана)
            $table->foreignId('affected_user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->text('reason')->nullable();
            $table->json('metadata')->nullable()->comment('JSON с до/после изменения');
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();

            $table->index('action_type');
            $table->index(['moderator_id', 'created_at']);
            $table->index(['affected_user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderation_logs');
    }
};
