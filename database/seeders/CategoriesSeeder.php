<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Овощеводство',
                'description' => 'Выращивание овощей в открытом грунте и теплицах',
                'color' => '#556b4d',
                'children' => [
                    'Томаты и перцы',
                    'Огурцы и зеленные',
                    'Корнеплоды',
                    'Капуста',
                ],
            ],
            [
                'name' => 'Плодовый сад',
                'description' => 'Деревья, кустарники, уход и обрезка',
                'color' => '#6b4423',
                'children' => [
                    'Яблони и груши',
                    'Косточковые',
                    'Ягодные кустарники',
                ],
            ],
            [
                'name' => 'Цветоводство',
                'description' => 'Декоративные растения и ландшафтный дизайн',
                'color' => '#a94442',
                'children' => [
                    'Многолетники',
                    'Однолетники',
                    'Розы',
                ],
            ],
            [
                'name' => 'Защита растений',
                'description' => 'Болезни, вредители, способы борьбы',
                'color' => '#3d2817',
                'children' => [
                    'Болезни',
                    'Вредители',
                    'Биозащита',
                ],
            ],
            [
                'name' => 'Почва и удобрения',
                'description' => 'Подготовка, мульчирование, подкормки',
                'color' => '#8a6d3b',
            ],
            [
                'name' => 'Техника и инструменты',
                'description' => 'Инвентарь, мини-техника, обслуживание',
                'color' => '#4a5642',
            ],
            [
                'name' => 'Фермерство',
                'description' => 'Профессиональное хозяйство, сбыт продукции',
                'color' => '#2d3d2d',
                'children' => [
                    'Растениеводство',
                    'Животноводство',
                    'Сбыт и логистика',
                ],
            ],
        ];

        foreach ($categories as $order => $data) {
            $children = $data['children'] ?? [];
            unset($data['children']);

            $parent = Category::firstOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['sort_order' => $order, 'is_active' => true])
            );

            foreach ($children as $childOrder => $childName) {
                Category::firstOrCreate(
                    ['name' => $childName],
                    [
                        'parent_id' => $parent->id,
                        'sort_order' => $childOrder,
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('✓ Создано категорий: ' . Category::count());
    }
}
