<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Food;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create test customer user
        $customer = User::create([
            'name' => 'Aziza Karimova',
            'email' => 'customer@test.com',
            'phone' => '+998901234567',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        // Create restaurant owners
        $restaurantOwner1 = User::create([
            'name' => 'Ali Restaurant Owner',
            'email' => 'restaurant1@test.com',
            'phone' => '+998902345678',
            'password' => Hash::make('password123'),
            'role' => 'restaurant',
            'is_active' => true,
        ]);

        $restaurantOwner2 = User::create([
            'name' => 'Zaynab Restaurant Owner',
            'email' => 'restaurant2@test.com',
            'phone' => '+998903456789',
            'password' => Hash::make('password123'),
            'role' => 'restaurant',
            'is_active' => true,
        ]);

        // Create restaurants
        $restaurant1 = Restaurant::create([
            'user_id' => $restaurantOwner1->id,
            'name' => 'Green Bowl Cafe',
            'description' => 'Fresh salads, healthy meals and organic food',
            'category' => 'Salad',
            'logo_url' => 'https://via.placeholder.com/150?text=Green+Bowl',
            'cover_image_url' => 'https://via.placeholder.com/1200x400?text=Green+Bowl+Cafe',
            'rating' => 4.8,
            'is_open' => true,
            'commission_rate' => 20,
        ]);

        $restaurant2 = Restaurant::create([
            'user_id' => $restaurantOwner2->id,
            'name' => 'Fast Food House',
            'description' => 'Delicious burgers, pizzas and fast food',
            'category' => 'Fast Food',
            'logo_url' => 'https://via.placeholder.com/150?text=Fast+Food',
            'cover_image_url' => 'https://via.placeholder.com/1200x400?text=Fast+Food+House',
            'rating' => 4.5,
            'is_open' => true,
            'commission_rate' => 25,
        ]);

        // Foods for restaurant 1
        Food::create([
            'restaurant_id' => $restaurant1->id,
            'name' => 'Green Bowl',
            'description' => 'Fresh salad with avocado, quinoa and lemon dressing',
            'price' => 24000,
            'category' => 'Salad',
            'image_url' => 'https://via.placeholder.com/400x300?text=Green+Bowl',
            'is_available' => true,
            'rating' => 4.9,
        ]);

        Food::create([
            'restaurant_id' => $restaurant1->id,
            'name' => 'Caesar Salad',
            'description' => 'Classic Caesar salad with crispy croutons and parmesan cheese',
            'price' => 22000,
            'category' => 'Salad',
            'image_url' => 'https://via.placeholder.com/400x300?text=Caesar+Salad',
            'is_available' => true,
            'rating' => 4.7,
        ]);

        Food::create([
            'restaurant_id' => $restaurant1->id,
            'name' => 'Greek Salad',
            'description' => 'Fresh vegetables with feta cheese and olives',
            'price' => 20000,
            'category' => 'Salad',
            'image_url' => 'https://via.placeholder.com/400x300?text=Greek+Salad',
            'is_available' => true,
            'rating' => 4.6,
        ]);

        Food::create([
            'restaurant_id' => $restaurant1->id,
            'name' => 'Detox Juice',
            'description' => 'Fresh pressed juice with apple, ginger and lemon',
            'price' => 12000,
            'category' => 'Drinks',
            'image_url' => 'https://via.placeholder.com/400x300?text=Detox+Juice',
            'is_available' => true,
            'rating' => 4.5,
        ]);

        // Foods for restaurant 2
        Food::create([
            'restaurant_id' => $restaurant2->id,
            'name' => 'Cheeseburger',
            'description' => 'Juicy burger with cheddar cheese, lettuce and tomato',
            'price' => 25000,
            'category' => 'Burgers',
            'image_url' => 'https://via.placeholder.com/400x300?text=Cheeseburger',
            'is_available' => true,
            'rating' => 4.7,
        ]);

        Food::create([
            'restaurant_id' => $restaurant2->id,
            'name' => 'Pepperoni Pizza',
            'description' => 'Classic pizza with pepperoni and mozzarella',
            'price' => 35000,
            'category' => 'Pizza',
            'image_url' => 'https://via.placeholder.com/400x300?text=Pepperoni+Pizza',
            'is_available' => true,
            'rating' => 4.8,
        ]);

        Food::create([
            'restaurant_id' => $restaurant2->id,
            'name' => 'Chicken Wings',
            'description' => '6 pieces spicy chicken wings with BBQ sauce',
            'price' => 28000,
            'category' => 'Wings',
            'image_url' => 'https://via.placeholder.com/400x300?text=Chicken+Wings',
            'is_available' => true,
            'rating' => 4.6,
        ]);

        Food::create([
            'restaurant_id' => $restaurant2->id,
            'name' => 'French Fries',
            'description' => 'Crispy golden fries with salt and ketchup',
            'price' => 8000,
            'category' => 'Sides',
            'image_url' => 'https://via.placeholder.com/400x300?text=French+Fries',
            'is_available' => true,
            'rating' => 4.4,
        ]);
    }
}
