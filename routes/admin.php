<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchasesDetailController;
use App\Http\Controllers\PurchasController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProofOfPaymentController;
use App\Http\Controllers\SalesDetailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    /**
     * El código define las rutas para un controlador de providers en una aplicación Laravel
     */

    Route::get('/proveedores', [ProviderController::class, 'index'])->name('providers');
    Route::post('/proveedores/data', [ProviderController::class, 'getData'])->name('providers.data');
    Route::post('/proveedores/search', [ProviderController::class, 'search'])->name('providers.search');
    Route::post('/proveedores/import', [ProviderController::class, 'import'])->name('providers.import');
    Route::post('/proveedores', [ProviderController::class, 'store'])->name('providers.store');
    Route::put('/proveedores/{id}', [ProviderController::class, 'update'])->name('providers.update');
    Route::delete('/proveedores/{id}', [ProviderController::class, 'destroy'])->name('providers.destroy');

     /**
     * El código define las rutas para un controlador de providers en una aplicación Laravel
     */

     Route::get('/clientes', [ClientController::class, 'index'])->name('clients');
     Route::post('/clientes/data', [ClientController::class, 'getData'])->name('clients.data');
     Route::post('/clientes/search', [ClientController::class, 'search'])->name('clients.search');
     Route::post('/clientes/import', [ClientController::class, 'import'])->name('clients.import');
     Route::post('/clientes', [ClientController::class, 'store'])->name('clients.store');
     Route::put('/clientes/{id}', [ClientController::class, 'update'])->name('clients.update');
     Route::delete('/clientes/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');/**

     * El código define las rutas para un controlador de categories en una aplicación Laravel
     */

    Route::get('/categorias', [CategoryController::class, 'index'])->name('categories');
    Route::post('/categorias/data', [CategoryController::class, 'getData'])->name('categories.data');
    Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/categorias/search', [CategoryController::class, 'search'])->name('categories.search');
    Route::post('/categorias/import', [CategoryController::class, 'import'])->name('categories.import');
    Route::put('/categorias/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categorias/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categorias/update-ids', [CategoryController::class, 'updateIDs'])->name('categories.update-ids');

    /**
     * El código define las rutas para un controlador de products en una aplicación Laravel
     */

    Route::get('/productos', [ProductController::class, 'index'])->name('products');
    Route::post('/productos/data', [ProductController::class, 'getData'])->name('products.data');
    Route::post('/productos/search', [ProductController::class, 'search'])->name('products.search');
    Route::post('/productos/searchsales', [ProductController::class, 'searchSales'])->name('products.searchSales');
    Route::post('/productos/import', [ProductController::class, 'import'])->name('products.import');
    Route::post('/productos', [ProductController::class, 'store'])->name('products.store');
    Route::put('/productos/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/productos/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    /**
     * El código define las rutas para un controlador de purchases en una aplicación Laravel
     */

     Route::get('/compras', [PurchasController::class, 'index'])->name('purchases');
     Route::post('/compras/data/product', [PurchasController::class, 'getData'])->name('purchases.data');
     Route::post('/compras', [PurchasController::class, 'store'])->name('purchases.store');
     Route::put('/compras/{id}', [PurchasController::class, 'update'])->name('purchases.update');
     Route::delete('/compras/{id}', [PurchasController::class, 'destroy'])->name('purchases.destroy');

     /**
     * El código define las rutas para un controlador de purchases en una aplicación Laravel
     */

     Route::get('/detalle-de-compras', [PurchasesDetailController::class, 'index'])->name('purchasesdetail');

     /**
      * El código define las rutas para un controlador de sales en una aplicación Laravel
      */

     Route::get('/ventas', [SaleController::class, 'index'])->name('sales');
     Route::post('/ventas/data', [SaleController::class, 'getData'])->name('sales.data');
     Route::post('/ventas', [SaleController::class, 'store'])->name('sales.store');
     Route::put('/ventas/{id}', [SaleController::class, 'update'])->name('sales.update');
     Route::delete('/ventas/{id}', [SaleController::class, 'destroy'])->name('sales.destroy');
/**
     * El código define las rutas para un controlador de purchases en una aplicación Laravel
     */

     Route::get('/detalle-de-ventas', [SalesDetailController::class, 'index'])->name('salesdetail');

     /**
     * El código define las rutas para un controlador de proofofpayment en una aplicación Laravel
     */

    Route::get('/comprobantes', [ProofOfPaymentController::class, 'index'])->name('proofofpayments');
    Route::post('/comprobantes/data', [ProofOfPaymentController::class, 'getData'])->name('proofofpayments.data');
    Route::post('/comprobantes/search', [ProofOfPaymentController::class, 'search'])->name('proofofpayments.search');
    Route::post('/comprobantes', [ProofOfPaymentController::class, 'store'])->name('proofofpayments.store');
    Route::put('/comprobantes/{id}', [ProofOfPaymentController::class, 'update'])->name('proofofpayments.update');
    Route::delete('/comprobantes/{id}', [ProofOfPaymentController::class, 'destroy'])->name('proofofpayments.destroy');

     /**
     * El código define las rutas para un controlador de employees en una aplicación Laravel
     */

     Route::get('/empleados', [EmployeeController::class, 'index'])->name('employees');
     Route::post('/empleados/data', [EmployeeController::class, 'getData'])->name('employees.data');
     Route::post('/empleados/search', [EmployeeController::class, 'search'])->name('employees.search');
     Route::post('/empleados/import', [EmployeeController::class, 'import'])->name('employees.import');
     Route::post('/empleados', [EmployeeController::class, 'store'])->name('employees.store');
     Route::put('/empleados/{id}', [EmployeeController::class, 'update'])->name('employees.update');
     Route::delete('/empleados/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    /**
     * El código define las rutas para un controlador de roles en una aplicación Laravel
     */
    Route::get('/tipos-de-usuario', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/tipos-de-usuario/search', [RoleController::class, 'search'])->name('roles.search');
    Route::get('/tipos-de-usuario/registrar', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/tipos-de-usuario', [RoleController::class, 'store'])->name('roles.store');
    Route::post('/tipos-de-usuario/data', [RoleController::class, 'getDataRules'])->name('roles.data');
    Route::get('/tipos-de-usuario/{id}/detalle', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/tipos-de-usuario/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/tipos-de-usuario/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/tipos-de-usuario/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');


    Route::resource('users', UserController::class);
    // Route::resource('tipos-de-usuario', RoleController::class);
});
