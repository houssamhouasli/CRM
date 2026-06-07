<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Commercial;
use App\Http\Controllers\OrderPrintController;
use App\Http\Controllers\DeliveryPrintController;
// ─── Public ───
Route::get('/', fn() => view('welcome'))->name('home');

// ─── Print & Review Routes (Internal) ───
Route::middleware(['auth'])->group(function () {
    Route::get('/order_email/{id}', function($id) {
        $order = App\Models\Order::with(['client', 'items.product', 'creator'])->findOrFail($id);
        return view('emails.new-order', [
            'order' => $order,
            'client' => $order->client,
            'items' => $order->items,
            'creator' => $order->creator,
        ]);
    })->name('order_email')->whereNumber('id'); 

    Route::get('/order_status_email/{id}', function($id) {
        $order = App\Models\Order::with('client')->findOrFail($id);
        return view('emails.status_updated', [
            'order' => $order,
            'newStatusLabel' => 'Livrée',
            'color' => '#27ae60', 
        ]);
    })->name('order_status_email')->whereNumber('id');

    // Print Routes
    Route::get('/orders/{order}/print', [OrderPrintController::class, 'show'])
        ->name('orders.print');

    Route::get('/deliveries/{delivery}/print', [DeliveryPrintController::class, 'show'])
        ->name('deliveries.print');
});

// ─── Auth Routes (Laravel Breeze) ─── 
require __DIR__ . '/auth.php';

// ─── Redirect after login ───
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->role) {
        'admin' => redirect('/admin'),
        'commercial' => redirect('/commercial'),
        'depositaire' => redirect('/depositaire'),
        'livreur' => redirect('/livreur'),
        default => redirect('/admin'),
    };
})->middleware('auth')->name('dashboard');




// ═══════════════════════════════════════════════
//  ADMIN
// ═══════════════════════════════════════════════
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('regions', Admin\RegionController::class)->except('show');
    Route::resource('clients', Admin\ClientController::class);
    Route::resource('products', Admin\ProductController::class)->except('show');
    Route::resource('users', Admin\AdminUserController::class);

    // Categories
    Route::get('categories', [Admin\ProductController::class, 'categories'])->name('categories.index');
    Route::get('categories/create', [Admin\ProductController::class, 'createCategory'])->name('categories.create');
    Route::post('categories', [Admin\ProductController::class, 'storeCategory'])->name('categories.store');
    Route::get('categories/{category}', [Admin\ProductController::class, 'showCategory'])->name('categories.show');
    Route::get('categories/{category}/edit', [Admin\ProductController::class, 'editCategory'])->name('categories.edit');
    Route::put('categories/{category}', [Admin\ProductController::class, 'updateCategory'])->name('categories.update');
    Route::delete('categories/{category}', [Admin\ProductController::class, 'destroyCategory'])->name('categories.destroy');

    // Orders
    Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{order}/download', [Admin\OrderController::class, 'download'])->name('orders.download');
    Route::put('orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('orders/{order}/status', fn($order) => redirect()->route('admin.orders.show', $order));

    // Stock
    Route::get('stock', [Admin\ProductController::class, 'stockMovements'])->name('stock.index');

    // Profile
    Route::get('profile', [Admin\ProfileController::class, 'edit'])->name('profile');
    Route::put('profile', [Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [Admin\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Export
    Route::get('export/all', [Admin\ExportController::class, 'exportAll'])->name('export.all');

    // Deliveries
    Route::get('deliveries', [Admin\DeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('deliveries/{delivery}', [Admin\DeliveryController::class, 'show'])->name('deliveries.show');

    // Returns
    Route::get('returns', [Admin\ReturnController::class, 'index'])->name('returns.index');
    Route::get('returns/{return}', [Admin\ReturnController::class, 'show'])->name('returns.show');
    Route::post('returns/{return}/validate', [Admin\ReturnController::class, 'validate'])->name('returns.validate');
    Route::post('returns/{return}/reject', [Admin\ReturnController::class, 'reject'])->name('returns.reject');
});

// ═══════════════════════════════════════════════
//  COMMERCIAL (Formerly Regional)
// ═══════════════════════════════════════════════
Route::prefix('commercial')->name('commercial.')->middleware(['auth', 'role:commercial'])->group(function () {
    Route::get('/', [Commercial\DashboardController::class, 'index'])->name('dashboard');

    // Clients (Full management for their region)
    Route::resource('clients', Commercial\ClientController::class);

    // Orders (Read-only)
    Route::get('orders', [Commercial\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [Commercial\OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{order}/download', [Commercial\OrderController::class, 'download'])->name('orders.download');

    // Deliveries (Read-only)
    Route::get('deliveries', [Commercial\DeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('deliveries/{delivery}', [Commercial\DeliveryController::class, 'show'])->name('deliveries.show');

    // Products (Read-only)
    Route::get('products', [Commercial\ProductController::class, 'index'])->name('products.index');
    Route::get('products/{product}', [Commercial\ProductController::class, 'show'])->name('products.show');

    // Profile
    Route::get('profile', [Commercial\ProfileController::class, 'edit'])->name('profile');
    Route::put('profile', [Commercial\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [Commercial\ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ═══════════════════════════════════════════════
//  DEPOSITAIRE (Dépôt)
// ═══════════════════════════════════════════════
Route::prefix('depositaire')->name('depositaire.')->middleware(['auth', 'role:depositaire'])->group(function () {
    Route::get('/', [App\Http\Controllers\Depositaire\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [App\Http\Controllers\Depositaire\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Depositaire\OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/create-delivery', [App\Http\Controllers\Depositaire\OrderController::class, 'createDelivery'])->name('orders.delivery.create');
    Route::post('/orders/{order}/create-delivery', [App\Http\Controllers\Depositaire\OrderController::class, 'storeDelivery'])->name('orders.delivery.store');
    Route::post('/orders/{order}/cancel', [App\Http\Controllers\Depositaire\OrderController::class, 'cancel'])->name('orders.cancel');

    // Statistics & Reports
    Route::get('/daily-totals', [App\Http\Controllers\Depositaire\DailyTotalController::class, 'index'])->name('daily-totals');

    Route::get('/restock', [App\Http\Controllers\Depositaire\RestockController::class, 'index'])->name('restock.index');
    Route::get('/restock/create', [App\Http\Controllers\Depositaire\RestockController::class, 'create'])->name('restock.create');
    Route::post('/restock', [App\Http\Controllers\Depositaire\RestockController::class, 'store'])->name('restock.store');
    Route::get('/restock/{order}', [App\Http\Controllers\Depositaire\RestockController::class, 'show'])->name('restock.show');
    Route::post('/restock/{order}/receive', [App\Http\Controllers\Depositaire\RestockController::class, 'receive'])->name('restock.receive');

    Route::get('/deliveries', [App\Http\Controllers\Depositaire\DeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('/deliveries/{delivery}', [App\Http\Controllers\Depositaire\DeliveryController::class, 'show'])->name('deliveries.show');
    Route::get('/stock', [App\Http\Controllers\Depositaire\StockController::class, 'index'])->name('stock.index');
    Route::get('/stock-movements', [App\Http\Controllers\Depositaire\StockController::class, 'movements'])->name('stock-movements.index');
    Route::get('/products/{product}', [App\Http\Controllers\Depositaire\StockController::class, 'showProduct'])->name('products.show');

    // Returns (Depositary can validate/reject returns for their depot)
    Route::get('/returns', [App\Http\Controllers\Depositaire\ReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/{return}', [App\Http\Controllers\Depositaire\ReturnController::class, 'show'])->name('returns.show');
    Route::post('/returns/{return}/validate', [App\Http\Controllers\Depositaire\ReturnController::class, 'validate'])->name('returns.validate');
    Route::post('/returns/{return}/reject', [App\Http\Controllers\Depositaire\ReturnController::class, 'reject'])->name('returns.reject');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Depositaire\ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Depositaire\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Depositaire\ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ═══════════════════════════════════════════════
//  LIVREUR (Camion)
// ═══════════════════════════════════════════════
Route::prefix('livreur')->name('livreur.')->middleware(['auth', 'role:livreur'])->group(function () {
    Route::get('/', [App\Http\Controllers\Livreur\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-truck', [App\Http\Controllers\Livreur\TruckController::class, 'index'])->name('truck.index');

    // Orders
    Route::get('/orders', [App\Http\Controllers\Livreur\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [App\Http\Controllers\Livreur\OrderController::class, 'create'])->name('orders.create');
    Route::get('/orders/{order}', [App\Http\Controllers\Livreur\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/pull', [App\Http\Controllers\Livreur\OrderController::class, 'pull'])->name('orders.pull');

    // Deliveries
    Route::get('/deliveries', [App\Http\Controllers\Livreur\DeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('/deliveries/{delivery}', [App\Http\Controllers\Livreur\DeliveryController::class, 'show'])->name('deliveries.show');
    Route::post('/deliveries/{delivery}/complete', [App\Http\Controllers\Livreur\DeliveryController::class, 'complete'])->name('deliveries.complete');
    Route::post('/deliveries/{delivery}/cancel', [App\Http\Controllers\Livreur\DeliveryController::class, 'cancel'])->name('deliveries.cancel');
    Route::post('/deliveries/{delivery}/accept-proposition', [App\Http\Controllers\Livreur\DeliveryController::class, 'acceptProposition'])->name('deliveries.accept-proposition');
    Route::post('/deliveries/{delivery}/reject-proposition', [App\Http\Controllers\Livreur\DeliveryController::class, 'rejectProposition'])->name('deliveries.reject-proposition');

    // Returns
    Route::get('/returns', [App\Http\Controllers\Livreur\ReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/{return}', [App\Http\Controllers\Livreur\ReturnController::class, 'show'])->name('returns.show');
    Route::get('/deliveries/{delivery}/returns/create', [App\Http\Controllers\Livreur\ReturnController::class, 'create'])->name('deliveries.returns.create');
    Route::post('/deliveries/{delivery}/returns', [App\Http\Controllers\Livreur\ReturnController::class, 'store'])->name('deliveries.returns.store');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Livreur\ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Livreur\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Livreur\ProfileController::class, 'updatePassword'])->name('profile.password');
});
 
