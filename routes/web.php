<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\WorkOrderCheckinController;
use App\Http\Controllers\PublicQuoteController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ActividadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación y dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Rutas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas para el CRUD (solo admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('cotizaciones', CotizacionController::class)
         ->parameters(['cotizaciones' => 'cotizacion']);
    Route::resource('products', ProductController::class);
    // Con resource se generan las rutas index, create, store, show, edit, update y destroy para work_orders
    Route::resource('work_orders', WorkOrderController::class)
         ->parameters(['work_orders' => 'workOrder']);
});

// Ruta para ver el mapa de check-ins (la restricción se maneja en el controlador)
Route::get('/map-checkins', [WorkOrderCheckinController::class, 'mapByDate'])->name('checkins.map');

// Rutas para técnicos (solo para usuarios con rol "técnico")
Route::middleware(['auth', 'tech'])->prefix('tech')->name('tech.')->group(function () {
    Route::get('work_orders', [WorkOrderController::class, 'indexForTech'])->name('work_orders.index');
    Route::get('work_orders/{workOrder}', [WorkOrderController::class, 'showForTech'])->name('work_orders.show');
    Route::get('work_orders/{workOrder}/edit', [WorkOrderController::class, 'editForTech'])->name('work_orders.edit');
    Route::patch('work_orders/{workOrder}', [WorkOrderController::class, 'updateForTech'])->name('work_orders.update');
    
    // Ruta para el check-in del técnico
    Route::post('work_orders/checkin', [WorkOrderController::class, 'checkinTech'])->name('work_orders.checkin');
});

// Rutas para el CRM (solo admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('crm/actividades', ActividadController::class)->names('actividades');
});

// Rutas de configuración (solo admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
});

// Rutas para autorizar o rechazar cotizaciones (solo admin)
Route::patch('cotizaciones/{cotizacion}/authorize', [CotizacionController::class, 'authorizeQuote'])
     ->name('cotizaciones.authorize')
     ->middleware(['auth', 'admin']);
Route::patch('cotizaciones/{cotizacion}/reject', [CotizacionController::class, 'rejectQuote'])
     ->name('cotizaciones.reject')
     ->middleware(['auth', 'admin']);

// Rutas públicas para la vista de cotización y descarga de PDF
Route::get('/public/quote/{token}', [PublicQuoteController::class, 'view'])->name('quotes.public_view');
Route::get('/public/quote/{token}/pdf', [PublicQuoteController::class, 'downloadPdf'])->name('quotes.pdf');

// Cargar rutas de autenticación (login, logout, etc.)
require __DIR__.'/auth.php';