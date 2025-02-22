<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Create categories
        $categoryNames = [
            'Medicines',
            'Vitamins & Supplements',
            'Sports Nutrition',
            'Skin & Beauty Care',
            'Mother & Baby Care',
            'Personal Care',
            'Homeopathy',
            'Optics'
        ];

        $categories = [];
        foreach ($categoryNames as $name) {
            $categories[] = Category::create(['name' => $name]);
        }

        // Create sample products
        $products = [
            [
                'name' => 'Pain Reliever',
                'slug' => Str::slug('Pain Reliever'),
                'price' => 15.00,
                'stock_quantity' => 100,
                'images' => json_encode(['pain_reliever.jpg']),
                'category' => 0, // Medicines
            ],
            [
                'name' => 'Vitamin C Supplement',
                'slug' => Str::slug('Vitamin C Supplement'),
                'price' => 25.00,
                'stock_quantity' => 50,
                'images' => json_encode(['vitamin_c.jpg']),
                'category' => 1, // Vitamins & Supplements
            ],
            [
                'name' => 'Whey Protein',
                'slug' => Str::slug('Whey Protein'),
                'price' => 30.00,
                'stock_quantity' => 70,
                'images' => json_encode(['whey_protein.jpg']),
                'category' => 2, // Sports Nutrition
            ],
            [
                'name' => 'Moisturizing Cream',
                'slug' => Str::slug('Moisturizing Cream'),
                'price' => 12.00,
                'stock_quantity' => 150,
                'images' => json_encode(['moisturizer.jpg']),
                'category' => 3, // Skin & Beauty Care
            ],
            [
                'name' => 'Baby Diapers',
                'slug' => Str::slug('Baby Diapers'),
                'price' => 20.00,
                'stock_quantity' => 200,
                'images' => json_encode(['baby_diapers.jpg']),
                'category' => 4, // Mother & Baby Care
            ],
            [
                'name' => 'Shampoo',
                'slug' => Str::slug('Shampoo'),
                'price' => 10.00,
                'stock_quantity' => 120,
                'images' => json_encode(['shampoo.jpg']),
                'category' => 5, // Personal Care
            ],
            [
                'name' => 'Homeopathic Cold Remedy',
                'slug' => Str::slug('Homeopathic Cold Remedy'),
                'price' => 18.00,
                'stock_quantity' => 80,
                'images' => json_encode(['homeopathic_remedy.jpg']),
                'category' => 6, // Homeopathy
            ],
            [
                'name' => 'Sunglasses',
                'slug' => Str::slug('Sunglasses'),
                'price' => 35.00,
                'stock_quantity' => 60,
                'images' => json_encode(['sunglasses.jpg']),
                'category' => 7, // Optics
            ],
            [
                'name' => 'Antacid Tablets',
                'slug' => Str::slug('Antacid Tablets'),
                'price' => 8.00,
                'stock_quantity' => 150,
                'images' => json_encode(['antacid_tablets.jpg']),
                'category' => 0, // Medicines
            ],
            [
                'name' => 'Multivitamin Tablets',
                'slug' => Str::slug('Multivitamin Tablets'),
                'price' => 22.00,
                'stock_quantity' => 100,
                'images' => json_encode(['multivitamin_tablets.jpg']),
                'category' => 1, // Vitamins & Supplements
            ],
        ];

        // Insert products
        foreach ($products as $data) {
            $product = Product::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'price' => $data['price'],
                'stock_quantity' => $data['stock_quantity'],
                'images' => $data['images'],
            ]);

            // Attach category if products have a many-to-many relationship
            if (isset($data['category'])) {
                $product->categories()->attach($categories[$data['category']]->id);
            }
        }
    }
}
