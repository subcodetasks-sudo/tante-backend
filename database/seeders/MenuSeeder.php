<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MostOrderedContent;
use App\Models\Product;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        MostOrderedContent::query()->updateOrCreate(
            ['id' => 1],
            [
                'title' => 'الأكثر طلبًا',
                'description' => 'أشهى الأطباق التي يفضلها عملاؤنا ويطلبونها باستمرار',
            ],
        );

        $menu = [
            [
                'name_ar' => 'مشاوي',
                'name_en' => 'Grills',
                'sort_order' => 1,
                'products' => [
                    ['name_ar' => 'مشكل مشاوي', 'name_en' => 'Mixed Grill', 'calories' => 850, 'price' => 89.00, 'is_flag' => true],
                    ['name_ar' => 'كباب لحم', 'name_en' => 'Lamb Kebab', 'calories' => 620, 'price' => 65.00, 'is_flag' => true],
                    ['name_ar' => 'شيش طاووق', 'name_en' => 'Shish Tawook', 'calories' => 480, 'price' => 55.00],
                    ['name_ar' => 'ريش غنم', 'name_en' => 'Lamb Chops', 'calories' => 720, 'price' => 95.00, 'is_flag' => true],
                ],
            ],
            [
                'name_ar' => 'مقبلات',
                'name_en' => 'Appetizers',
                'sort_order' => 2,
                'products' => [
                    ['name_ar' => 'حمص', 'name_en' => 'Hummus', 'calories' => 220, 'price' => 18.00],
                    ['name_ar' => 'متبل', 'name_en' => 'Mutabbal', 'calories' => 190, 'price' => 18.00],
                    ['name_ar' => 'فتوش', 'name_en' => 'Fattoush', 'calories' => 160, 'price' => 22.00, 'is_flag' => true],
                    ['name_ar' => 'تبولة', 'name_en' => 'Tabbouleh', 'calories' => 140, 'price' => 20.00],
                ],
            ],
            [
                'name_ar' => 'أطباق رئيسية',
                'name_en' => 'Main Dishes',
                'sort_order' => 3,
                'products' => [
                    ['name_ar' => 'مندي لحم', 'name_en' => 'Lamb Mandi', 'calories' => 780, 'price' => 75.00, 'is_flag' => true],
                    ['name_ar' => 'كبسة دجاج', 'name_en' => 'Chicken Kabsa', 'calories' => 690, 'price' => 58.00],
                    ['name_ar' => 'مقلوبة', 'name_en' => 'Maqluba', 'calories' => 710, 'price' => 62.00],
                ],
            ],
            [
                'name_ar' => 'مشروبات',
                'name_en' => 'Drinks',
                'sort_order' => 4,
                'products' => [
                    ['name_ar' => 'عصير برتقال طازج', 'name_en' => 'Fresh Orange Juice', 'calories' => 110, 'price' => 15.00],
                    ['name_ar' => 'ليمون بالنعناع', 'name_en' => 'Lemon Mint', 'calories' => 90, 'price' => 14.00],
                    ['name_ar' => 'لبن عيران', 'name_en' => 'Ayran', 'calories' => 80, 'price' => 10.00],
                ],
            ],
            [
                'name_ar' => 'حلويات',
                'name_en' => 'Desserts',
                'sort_order' => 5,
                'products' => [
                    ['name_ar' => 'كنافة', 'name_en' => 'Kunafa', 'calories' => null, 'price' => null, 'weights' => [
                        ['weight' => '250g', 'calories' => '350', 'price' => 18.00, 'sort_order' => 1],
                        ['weight' => '500g', 'calories' => '420', 'price' => 28.00, 'sort_order' => 2],
                        ['weight' => '1kg', 'calories' => '650', 'price' => 50.00, 'sort_order' => 3],
                    ]],
                    ['name_ar' => 'بقلاوة', 'name_en' => 'Baklava', 'calories' => 380, 'price' => 25.00],
                    ['name_ar' => 'أم علي', 'name_en' => 'Om Ali', 'calories' => 420, 'price' => 24.00],
                ],
            ],
        ];

        foreach ($menu as $categoryData) {
            $products = $categoryData['products'];
            unset($categoryData['products']);

            $category = Category::query()->updateOrCreate(
                ['name_en' => $categoryData['name_en']],
                $categoryData,
            );

            foreach ($products as $productData) {
                $weights = $productData['weights'] ?? [];
                unset($productData['weights']);

                $product = Product::query()->updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'name_en' => $productData['name_en'],
                    ],
                    [
                        ...$productData,
                        'is_active' => true,
                        'is_flag' => $productData['is_flag'] ?? false,
                    ],
                );

                $product->weights()->delete();

                foreach ($weights as $weightData) {
                    $product->weights()->create($weightData);
                }
            }
        }
    }
}
