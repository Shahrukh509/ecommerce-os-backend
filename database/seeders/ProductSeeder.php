<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
use Illuminate\Support\Arr;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prepare a placeholder image URL for media
        $placeholderImageUrl = 'https://img.freepik.com/free-photo/top-view-arrangement-with-pumpkins-baked-products_23-2148234234.jpg?t=st=1722886858~exp=1722890458~hmac=b758059e156de618df9e4a9f469c9d14e5f1c1c9b8f83bf8fde26d10f31f31a9&w=826';

        // Download the placeholder image once and store it locally
        $imageContents = file_get_contents($placeholderImageUrl);
        $imagePath = 'products/placeholder.jpg';
        Storage::put($imagePath, $imageContents);

        // Use Faker to generate fake data
        $faker = Faker::create();

        // Use batch insertion for products
        $products = [];
        for ($i = 1; $i <= 100000; $i++) {
            $products[] = [
                'category_id' => 1,
                'parent_id' => null,
                'name' => $faker->word,
                'slug' => \Illuminate\Support\Str::slug($faker->word),
                'price' => Arr::random(['100', '200', '300','400','500','600','700','800','900']),
                'size' => Arr::random(['S', 'M', 'L']),
                'color' => Arr::random(['black', 'pink', 'yellow', 'red', 'blue']),
                'sku' => 'sku-' . $i,
                'description' => 'Lorem ipsum is a great product with great reviews; you better buy this product.',
                'is_active' => 1
            ];

            if (count($products) >= 1000) {
                DB::table('products')->insert($products);
                $products = [];
            }
        }

        if (count($products) > 0) {
            DB::table('products')->insert($products);
        }

        // Now assign media to products
        Product::chunkById(1000, function ($products) use ($imagePath) {
            foreach ($products as $product) {
                $productModel = Product::find($product->id);
                $productModel->addMedia(storage_path('app/' . $imagePath))
                    ->preservingOriginal()
                    ->toMediaCollection('avatars');
            }
        });

        // Clean up the local placeholder image file after seeding
        Storage::delete($imagePath);
    }
}
