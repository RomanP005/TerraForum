<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Сброс кэша прав
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Создание прав
        $permissions = [
            // Темы
            'create themes', 'edit own themes', 'edit any themes',
            'delete own themes', 'delete any themes',
            'pin themes', 'close themes',

            // Сообщения
            'create posts', 'edit own posts', 'edit any posts',
            'delete own posts', 'delete any posts',

            // Комментарии
            'create comments', 'edit own comments', 'delete any comments',

            // Голосование
            'vote',

            // Услуги
            'create services', 'edit own services', 'delete any services',
            'approve services',

            // Новости
            'create news', 'edit news', 'delete news',

            // Модерация
            'warn users', 'ban users', 'view moderation logs',

            // Админ
            'manage users', 'manage roles', 'manage categories', 'access admin panel',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Роль admin — все права
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());

        // Роль moderator
        $moderator = Role::firstOrCreate(['name' => 'moderator', 'guard_name' => 'web']);
        $moderator->givePermissionTo([
            'create themes', 'create posts', 'create comments', 'vote',
            'edit any themes', 'edit any posts', 'delete any themes',
            'delete any posts', 'delete any comments',
            'pin themes', 'close themes',
            'warn users', 'view moderation logs',
            'approve services', 'access admin panel',
        ]);

        // Роль user (по умолчанию для новых регистраций)
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $user->givePermissionTo([
            'create themes', 'edit own themes', 'delete own themes',
            'create posts', 'edit own posts', 'delete own posts',
            'create comments', 'edit own comments',
            'vote',
            'create services', 'edit own services',
        ]);

        $this->command->info('✓ Роли и права созданы');
    }
}
