<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Prepare a placeholder image URL for media
        $placeholderImage = 'https://img.freepik.com/premium-photo/picture-man-with-shirt-that-says-hair_1057389-84899.jpg?w=740';
        $faker = Faker::create();

        for ($i = 1; $i <= 2000; $i++) {
            // Create a user
            $user = User::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('admin@123'), // Default password
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'is_active' => 1,
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'city' => $faker->city(),
                'zip_code' => $faker->postcode,
                'ip_address' => $faker->ipv4,
            ]);

            // Download the placeholder image
            $imageContents = file_get_contents($placeholderImage);
            $imagePath = 'public/avatars/user_' . $i . '.jpg';

            // Store the image locally
            Storage::put($imagePath, $imageContents);

            // Attach the image to the user
            $user->addMedia(storage_path('app/' . $imagePath))
                ->preservingOriginal()
                ->toMediaCollection('avatars');

            // Clean up the local image file after uploading
            Storage::delete($imagePath);
        }

    

    }
}
