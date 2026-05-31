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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [

            'create themes', 'edit own themes', 'edit any themes',
            'delete own themes', 'delete any themes',
            'pin themes', 'close themes',


            'create posts', 'edit own posts', 'edit any posts',
            'delete own posts', 'delete any posts',


            'create comments', 'edit own comments', 'delete any comments',


            'vote',


            'create services', 'edit own services', 'delete any services',
            'approve services',


            'create news', 'edit news', 'delete news',


            'warn users', 'ban users', 'view moderation logs',


            'manage users', 'manage roles', 'manage categories', 'access admin panel',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }


        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());


        $moderator = Role::firstOrCreate(['name' => 'moderator', 'guard_name' => 'web']);
        $moderator->givePermissionTo([
            'create themes', 'create posts', 'create comments', 'vote',
            'edit any themes', 'edit any posts', 'delete any themes',
            'delete any posts', 'delete any comments',
            'pin themes', 'close themes',
            'warn users', 'view moderation logs',
            'approve services', 'access admin panel',
        ]);


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
