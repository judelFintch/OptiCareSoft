<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Patients\PatientController;
use App\Http\Controllers\Appointments\AppointmentController;
use App\Http\Controllers\Reception\ReceptionController;
use App\Http\Controllers\Consultations\ConsultationController;
use App\Http\Controllers\Cashier\CashierController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Optical\OpticalController;
use App\Http\Controllers\Pharmacy\PharmacyController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Patients
    Route::resource('patients', PatientController::class);

    // Appointments
    Route::resource('appointments', AppointmentController::class);
    Route::patch('appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::patch('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

    // Reception
    Route::prefix('reception')->name('reception.')->group(function () {
        Route::get('/', [ReceptionController::class, 'index'])->name('index');
        Route::post('/open-visit', [ReceptionController::class, 'openVisit'])->name('open-visit');
        Route::patch('/visits/{visit}/close', [ReceptionController::class, 'closeVisit'])->name('close-visit');
        Route::patch('/visits/{visit}/status', [ReceptionController::class, 'updateStatus'])->name('update-status');
    });

    // Consultations
    Route::resource('consultations', ConsultationController::class);
    Route::patch('consultations/{consultation}/sign', [ConsultationController::class, 'sign'])->name('consultations.sign');
    Route::patch('consultations/{consultation}/complete', [ConsultationController::class, 'complete'])->name('consultations.complete');

    // Optical
    Route::prefix('optical')->name('optical.')->group(function () {
        Route::get('/', [OpticalController::class, 'index'])->name('index');
        Route::resource('orders', \App\Http\Controllers\Optical\OpticalOrderController::class);
        Route::patch('orders/{order}/status', [OpticalController::class, 'updateStatus'])->name('orders.status');
    });

    // Pharmacy
    Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
        Route::get('/', [PharmacyController::class, 'index'])->name('index');
        Route::resource('products', \App\Http\Controllers\Pharmacy\ProductController::class);
    });

    // Cashier
    Route::prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/', [CashierController::class, 'index'])->name('index');
        Route::resource('invoices', \App\Http\Controllers\Cashier\InvoiceController::class);
        Route::get('invoices/{invoice}/pdf', [\App\Http\Controllers\Cashier\InvoiceController::class, 'invoicePdf'])->name('invoices.pdf');
        Route::get('invoices/{invoice}/receipt', [\App\Http\Controllers\Cashier\InvoiceController::class, 'receiptPdf'])->name('invoices.receipt');
        Route::post('invoices/{invoice}/payment', [CashierController::class, 'addPayment'])->name('payment');
        Route::patch('invoices/{invoice}/cancel', [CashierController::class, 'cancelInvoice'])->name('cancel');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/daily', [ReportController::class, 'daily'])->name('daily');
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
        Route::get('/patients', [ReportController::class, 'patients'])->name('patients');
    });

    // Admin
    Route::prefix('admin')->name('admin.')->middleware('role:Admin|Manager')->group(function () {
        Route::resource('users', UserController::class)->names('users');
        Route::get('settings', [SettingController::class, 'index'])->name('settings');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('activity-log', fn() => view('pages.admin.activity-log'))->name('activity-log');
    });

});

require __DIR__.'/auth.php';
