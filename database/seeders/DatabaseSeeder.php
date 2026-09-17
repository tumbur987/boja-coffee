<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@boja.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Default user
        User::create([
            'name' => 'User',
            'email' => 'user@boja.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
        ]);

        // Categories
        $milk = Category::create(['name' => 'Milk Based']);
        $coffee = Category::create(['name' => 'Coffee Based']);
        $nonCoffee = Category::create(['name' => 'Non Coffee']);
        $snack = Category::create(['name' => 'Snack']);

        // Products
        Product::create(['category_id' => $milk->id, 'name' => 'Gula Aren', 'stock' => 100, 'price' => 23000]);
        Product::create(['category_id' => $milk->id, 'name' => 'Vanilla Latte', 'stock' => 80, 'price' => 25000]);
        Product::create(['category_id' => $coffee->id, 'name' => 'Espresso', 'stock' => 200, 'price' => 18000]);
        Product::create(['category_id' => $coffee->id, 'name' => 'Americano', 'stock' => 150, 'price' => 20000]);
        Product::create(['category_id' => $nonCoffee->id, 'name' => 'Matcha Latte', 'stock' => 90, 'price' => 22000]);
        Product::create(['category_id' => $snack->id, 'name' => 'Croissant', 'stock' => 50, 'price' => 15000]);

        // Tables
        Table::create(['number' => '1', 'code' => 'MJA001']);
        Table::create(['number' => '2', 'code' => 'MJA002']);
        Table::create(['number' => '3', 'code' => 'MJA003']);
        Table::create(['number' => '4', 'code' => 'MJA004']);
        Table::create(['number' => '5', 'code' => 'MJA005']);

        $this->call(SettingSeeder::class);
    }
}
