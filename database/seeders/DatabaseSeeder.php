<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'System Administrator with full permissions',
        ]);

        $managerRole = Role::create([
            'name' => 'manager',
            'description' => 'Inventory Manager who can view inventory and adjust stock',
        ]);

        $staffRole = Role::create([
            'name' => 'staff',
            'description' => 'Regular Staff member who can view inventory and log adjustments',
        ]);

        // 2. Seed Users & Mappings
        $admin = User::create([
            'name' => 'Quantum Admin',
            'email' => 'admin@quantum.com',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach($adminRole);

        $manager = User::create([
            'name' => 'Quantum Manager',
            'email' => 'manager@quantum.com',
            'password' => Hash::make('password'),
        ]);
        $manager->roles()->attach($managerRole);

        $staff = User::create([
            'name' => 'Quantum Staff',
            'email' => 'staff@quantum.com',
            'password' => Hash::make('password'),
        ]);
        $staff->roles()->attach($staffRole);

        // 3. Seed Categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'description' => 'Smartphones, Laptops, Wearables, etc.',
        ]);

        $office = Category::create([
            'name' => 'Office Supplies',
            'description' => 'Pens, Notebooks, Desks, etc.',
        ]);

        $wearables = Category::create([
            'name' => 'Wearables',
            'description' => 'Smartwatches, fitness trackers, etc.',
        ]);

        // 4. Seed Suppliers
        Supplier::create([
            'name' => 'NexusTech Distribution',
            'email' => 'nexus.dist@example.com',
            'phone' => '+15550199',
            'address' => '100 Silicon Blvd, San Jose, CA',
        ]);

        Supplier::create([
            'name' => 'Apex Office Solutions',
            'email' => 'apex.office@example.com',
            'phone' => '+15550233',
            'address' => '250 Commerce Pkwy, Austin, TX',
        ]);

        // 5. Seed Products
        // Item X (stock 15, threshold 10) - Healthy
        Product::create([
            'category_id' => $electronics->id,
            'sku' => 'SKU-ITEM-X',
            'name' => 'Quantum Smart Display',
            'description' => '8-inch smart display with voice assistant integration.',
            'price' => 129.99,
            'quantity' => 15,
            'min_threshold' => 10,
        ]);

        // Item Y (stock 3, threshold 5) - Low stock initially
        Product::create([
            'category_id' => $office->id,
            'sku' => 'SKU-ITEM-Y',
            'name' => 'Ergonomic Standing Desk',
            'description' => 'Adjustable height standing desk with memory presets.',
            'price' => 349.99,
            'quantity' => 3,
            'min_threshold' => 5,
        ]);

        // Item Z (stock 20, threshold 10) - Healthy
        Product::create([
            'category_id' => $wearables->id,
            'sku' => 'SKU-ITEM-Z',
            'name' => 'Quantum Fit Band v3',
            'description' => 'Waterproof fitness tracker with heart-rate sensor.',
            'price' => 89.99,
            'quantity' => 20,
            'min_threshold' => 10,
        ]);
    }
}
