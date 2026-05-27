<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Овощеводство',        'children' => ['Томаты и перцы', 'Огурцы и зеленные', 'Корнеплоды', 'Капуста']],
            ['name' => 'Плодовый сад',         'children' => ['Яблони и груши', 'Косточковые', 'Ягодные кустарники']],
            ['name' => 'Цветоводство',          'children' => ['Многолетники', 'Однолетники', 'Розы']],
            ['name' => 'Защита растений',       'children' => ['Болезни', 'Вредители', 'Биозащита']],
            ['name' => 'Почва и удобрения',     'children' => []],
            ['name' => 'Техника и инструменты', 'children' => []],
            ['name' => 'Фермерство',            'children' => ['Растениеводство', 'Животноводство', 'Сбыт и логистика']],
        ];

        foreach ($categories as $order => $data) {
            $parent = \App\Models\Category::where('name', $data['name'])->first();

            if (!$parent) {
                $parent = \App\Models\Category::create([
                    'name'       => $data['name'],
                    'slug'       => Str::slug($data['name']),  // ← явно передаём slug
                    'sort_order' => $order,
                    'is_active'  => true,
                ]);
            }

            foreach ($data['children'] as $childOrder => $childName) {
                if (!\App\Models\Category::where('name', $childName)->exists()) {
                    \App\Models\Category::create([
                        'name'       => $childName,
                        'slug'       => Str::slug($childName), // ← и здесь
                        'parent_id'  => $parent->id,
                        'sort_order' => $childOrder,
                        'is_active'  => true,
                    ]);
                }
            }
        }

        $this->command->info('Категорий создано: ' . \App\Models\Category::count());
    }
}
