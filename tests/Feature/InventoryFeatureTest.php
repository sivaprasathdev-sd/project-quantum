<?php

use App\Events\StockLevelChanged;
use App\Jobs\SendLowStockAlertEmail;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

test('guests are redirected to login', function () {
    $this->get('/dashboard')->assertRedirect(route('login'));
    $this->get('/products')->assertRedirect(route('login'));
});

test('authenticated users can access dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/dashboard')->assertStatus(200);
});

test('only admins can perform product CRUD', function () {
    // Setup roles
    $adminRole = Role::create(['name' => 'admin']);
    $staffRole = Role::create(['name' => 'staff']);

    $admin = User::factory()->create();
    $admin->roles()->attach($adminRole);

    $staff = User::factory()->create();
    $staff->roles()->attach($staffRole);

    $category = Category::create(['name' => 'Tech']);

    // Admin can create
    $this->actingAs($admin)->post('/products', [
        'category_id' => $category->id,
        'sku' => 'TEST-01',
        'name' => 'Test Laptop',
        'price' => 999.99,
        'quantity' => 20,
        'min_threshold' => 5,
    ])->assertRedirect(route('products.index'));

    $this->assertDatabaseHas('products', ['sku' => 'TEST-01']);

    $product = Product::where('sku', 'TEST-01')->first();

    // Staff cannot update
    $this->actingAs($staff)->put("/products/{$product->id}", [
        'category_id' => $category->id,
        'sku' => 'TEST-01-UPDATED',
        'name' => 'Updated Name',
        'price' => 888.88,
        'min_threshold' => 5,
    ])->assertStatus(403);

    // Admin can update
    $this->actingAs($admin)->put("/products/{$product->id}", [
        'category_id' => $category->id,
        'sku' => 'TEST-01-UPDATED',
        'name' => 'Updated Name',
        'price' => 888.88,
        'min_threshold' => 5,
    ])->assertRedirect(route('products.index'));

    $this->assertDatabaseHas('products', ['sku' => 'TEST-01-UPDATED']);

    // Staff cannot delete
    $this->actingAs($staff)->delete("/products/{$product->id}")->assertStatus(403);

    // Admin can delete
    $this->actingAs($admin)->delete("/products/{$product->id}")->assertRedirect(route('products.index'));
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

test('staff and managers can adjust stock level', function () {
    $staffRole = Role::create(['name' => 'staff']);
    $staff = User::factory()->create();
    $staff->roles()->attach($staffRole);

    $category = Category::create(['name' => 'Tech']);
    $product = Product::create([
        'category_id' => $category->id,
        'sku' => 'ADJ-01',
        'name' => 'Adjustable Item',
        'price' => 10,
        'quantity' => 50,
        'min_threshold' => 5,
    ]);

    $this->actingAs($staff)->post("/products/{$product->id}/adjust", [
        'quantity_changed' => -10,
        'reason' => 'Damaged inventory',
    ])->assertRedirect(route('products.index'));

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'quantity' => 40,
    ]);

    $this->assertDatabaseHas('stock_logs', [
        'product_id' => $product->id,
        'user_id' => $staff->id,
        'quantity_changed' => -10,
        'type' => 'out',
        'reason' => 'Damaged inventory',
    ]);
});

test('adjusting stock down below threshold dispatches low stock job and email', function () {
    Queue::fake();
    Event::fake([StockLevelChanged::class]);

    $adminRole = Role::create(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->roles()->attach($adminRole);

    $category = Category::create(['name' => 'Tech']);
    $product = Product::create([
        'category_id' => $category->id,
        'sku' => 'WARN-01',
        'name' => 'Warning Item',
        'price' => 10,
        'quantity' => 15,
        'min_threshold' => 10,
    ]);

    $stockService = app(StockService::class);
    
    // Acting as admin to authenticate Auth::id() inside StockService
    $this->actingAs($admin);
    
    $stockService->adjustStock($product, -6, 'out', 'Sale');

    // Verify stock is 9 (below 10)
    expect($product->fresh()->quantity)->toBe(9);

    // Verify Event was dispatched
    Event::assertDispatched(StockLevelChanged::class);

    // Manually trigger listener handle to check if job is dispatched since we faked events
    $listener = new \App\Listeners\CheckStockThreshold();
    $event = new StockLevelChanged($product->fresh());
    $listener->handle($event);

    Queue::assertPushed(SendLowStockAlertEmail::class, function ($job) use ($product) {
        return $job->product->id === $product->id;
    });
});
