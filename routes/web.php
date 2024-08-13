<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchasingInvoiceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SafeController;
use App\Http\Controllers\SellingInvoiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('auth:employee')->group(function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // settings
    Route::resource('settings', SettingController::class);

    // notifications
    Route::post('/notifications', [DashboardController::class, 'notification_read'])->name('notification')->middleware('permission:delete_notifications');

    // activities
    Route::resource('activities', ActivityLogController::class);

    // shifts
    Route::resource('shifts', ShiftController::class);

    // employees
    Route::resource('employees', EmployeeController::class);
    Route::get('employees/{employee}/change-status', [EmployeeController::class, 'changeStatus'])->name('employees.change-status');
    Route::post('employees/{employee}/change-role', [EmployeeController::class, 'changeRole'])->name('employees.change-role');

    // roles
    Route::resource('roles', RoleController::class);
    Route::post('roles/{role}/delete-role', [RoleController::class, 'destroy'])->name('roles.delete-role');

    // customers
    Route::resource('customers', CustomerController::class);
    Route::put('customers/{customer}/repayment', [CustomerController::class, 'repayment'])->name('customers.repayment');
    Route::put('customers/{customer}/repayment_for_customer', [CustomerController::class, 'repayment_for_customer'])->name('customers.repayment_for_customer');
    Route::get('customers/transactions/print', [CustomerController::class, 'print_transaction'])->name('customers.print_transaction');

    // suppliers
    Route::resource('suppliers', SupplierController::class);
    Route::put('suppliers/{supplier}/repayment', [SupplierController::class, 'repayment'])->name('suppliers.repayment');
    Route::get('suppliers/transactions/print', [SupplierController::class, 'print_transaction'])->name('suppliers.print_transaction');


    // products
    Route::resource('products', ProductController::class);
    
    // warehouse
    Route::resource('warehouse', WarehouseProductController::class);
    
    // purchasing_invoice
    Route::resource('purchasing_invoices', PurchasingInvoiceController::class);
    Route::get('purchasing_invoices/{purchasing_invoice}/print', [PurchasingInvoiceController::class, 'print'])->name('purchasing_invoices.print');
    Route::put('purchasing_invoices/{purchasing_invoice}/repayment', [PurchasingInvoiceController::class, 'repayment'])->name('purchasing_invoices.repayment');

    // invoices
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::put('invoices/{invoice}/repayment', [InvoiceController::class, 'repayment'])->name('invoices.repayment');

    // selling_invoice
    Route::resource('selling_invoices', SellingInvoiceController::class);
    Route::get('selling_invoices/{selling_invoice}/print', [SellingInvoiceController::class, 'print'])->name('selling_invoices.print');
    Route::put('selling_invoices/{selling_invoice}/repayment', [SellingInvoiceController::class, 'repayment'])->name('selling_invoices.repayment');
    Route::put('selling_invoices/{selling_invoice}/repayment_for_customer', [SellingInvoiceController::class, 'repayment_for_customer'])->name('selling_invoices.repayment_for_customer');
    Route::put('selling_invoices/{selling_invoice}/delivery', [SellingInvoiceController::class, 'delivery'])->name('selling_invoices.delivery');
    Route::put('selling_invoices/{selling_invoice}/payment_fund', [SellingInvoiceController::class, 'payment_fund'])->name('selling_invoices.payment_fund');
    
    // safe
    Route::get('safe', [SafeController::class, 'index'])->name('safe');

});


require __DIR__.'/auth.php';