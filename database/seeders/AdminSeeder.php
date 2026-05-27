<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'petrov231205@gmail.com'],
            [
                'name'     => 'Admin',
                'password' => bcrypt('qweqwe123'),
            ]
        );
        $admin->assignRole('admin');

        $moder = User::firstOrCreate(
            ['email' => 'rommeo2312056@gmail.com'],
            [
                'name'     => 'Moderator',
                'password' => bcrypt('qweqwe123'),
            ]
        );
        $moder->assignRole('moderator');

        $this->command->info('Администратор и модератор созданы');
    }
}
