<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\PickupRequestController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffAccountController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\UserManagementController;

//Admin Dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

//Staff Accounts Creation
Route::get('/staff/register', [StaffAccountController::class, 'create'])->name('staff.register');

Route::post('/staff/register', [StaffAccountController::class, 'store'])->name('staff.store');


// Staff Login
Route::get('/staff/login', [StaffAuthController::class, 'showLoginForm'])->name('staff.login');

Route::post('/staff/login', [StaffAuthController::class, 'login'])->name('staff.login.post');

Route::post('/staff/logout', [StaffAuthController::class, 'logout'])->name('staff.logout');


//Registrar
Route::get('/admin/registrar_office/dashboard', [DashboardController::class, 'registrarDashboard'])->name('registrar.dashboard');

Route::get('/admin/registrar_office/documents', [RegistrarController::class, 'index'])->name('documents');

Route::get('/admin/registrar_office/documents/addDocument', [RegistrarController::class, 'create'])->name('addDocument');

Route::post('/admin/registrar_office/documents/storeDocument', [RegistrarController::class, 'store'])->name('store_document');

Route::get('/admin/registrar_office/documents/{id}/edit', [RegistrarController::class, 'edit'])->name('documents.edit');

Route::put('/admin/registrar_office/documents/{id}', [RegistrarController::class, 'update'])->name('documents.update');

Route::delete('/admin/registrar_office/documents/{id}', [RegistrarController::class, 'destroy'])->name('documents.destroy');

Route::resource('/admin/registrar_office/pickup_requests', PickupRequestController::class);

Route::get('/registrar/pickup-requests', [RegistrarController::class, 'index'])->name('registrar.pickup_request');

Route::get('/admin/accounting_office/pickup_requests/create', [PickUpRequestController::class, 'create'])->name('pickup_requests.create');

Route::get('/registrar/pickup-requests/{id}', [PickupRequestController::class, 'registrarShow']);


Route::post('/admin/registrar_office/pickup_requests', [PickUpRequestController::class, 'store'])->name('pickup_requests.store');

Route::get('/admin/registrar_office/pickup_requests/{id}/edit', [PickupRequestController::class, 'edit'])->name('pickup_requests.edit');

Route::put('/admin/registrar_office/pickup_requests/{id}', [PickupRequestController::class, 'update'])->name('pickup_requests.update');

Route::get('/admin/registrar/archive', [PickUpRequestController::class, 'registrar_archive'])->name('registrar.archive');

Route::put('/pickup-requests/{id}/restore', [PickUpRequestController::class, 'registrar_restore'])->name('restore');

Route::delete('/pickup-requests/{id}/force-delete', [PickUpRequestController::class, 'registrar_forceDelete'])->name('forceDelete');





//Accounting
Route::get('/admin/accounting_office/dashboard', [DashboardController::class, 'accountingDashboard'])->name('accounting.dashboard');

Route::get('/admin/accounting_office/pickup_requests', [PickupRequestController::class, 'accounting_index'])->name('accounting.pickup_requests.index');

Route::get('/admin/accounting_office/pickup_requests/create', [PickupRequestController::class, 'accounting_create'])->name('accounting.pickup_requests.create');

Route::post('/admin/accounting_office/pickup_requests', [PickupRequestController::class, 'accounting_store'])->name('accounting.pickup_requests.store');

Route::get('/accounting/verify_pickup_requests/verify-id-no-{id}', [PickUpRequestController::class, 'show'])->name('accounting.pickup_requests.show');

Route::patch('/accounting/verify/{id}', [PickUpRequestController::class, 'verify'])->name('accounting.verify');

Route::get('/admin/accounting_office/pickup_requests/{id}/edit', [PickupRequestController::class, 'accounting_edit'])->name('accounting.pickup_requests.edit');

Route::put('/admin/accounting_office/pickup_requests/{id}', [PickupRequestController::class, 'accounting_update'])->name('accounting.pickup_requests.update');

Route::delete('/admin/accounting_office/documents/{id}', [PickupRequestController::class, 'accounting_destroy'])->name('accounting.destroy');

Route::prefix('accounting')->name('accounting.')->group(function () {
    Route::get('/archive', [PickUpRequestController::class, 'accounting_archive'])->name('archive');
    Route::put('/restore/{id}', [PickUpRequestController::class, 'accounting_restore'])->name('restore');
    Route::delete('/forceDelete/{id}', [PickUpRequestController::class, 'accounting_forceDelete'])->name('forceDelete');
});

Route::patch('/accounting/payment-methods/{id}/toggle', [PaymentMethodController::class, 'toggleActive'])->name('accounting.toggle_payment_method');




// Accounting Payments
Route::resource('/admin/accounting_office/payments', PaymentsController::class);
Route::get('/admin/accounting_office/payments', [PaymentsController::class, 'index'])->name('accounting.payments');
Route::get('/admin/accounting_office/payments/create', [PaymentsController::class, 'create'])->name('payments.create');
Route::post('/admin/accounting_office/payments', [PaymentsController::class, 'store'])->name('payments.store');
Route::get('/admin/accounting_office/payments/{id}/edit', [PaymentsController::class, 'edit'])->name('payments.edit');
Route::put('/admin/accounting_office/payments/{id}', [PaymentsController::class, 'update'])->name('payments.update');
Route::delete('/admin/accounting_office/payments/{id}', [PaymentsController::class, 'destroy'])->name('payments.destroy');

Route::get('/accounting/payment-settings', [PaymentMethodController::class, 'index'])->name('accounting.payment_settings');

Route::get('/accounting/payment-methods/create', [PaymentMethodController::class, 'create'])->name('accounting.payment_methods.create');

Route::post('/accounting/save-payment-methods', [PaymentMethodController::class, 'savePaymentMethods'])->name('accounting.savePaymentMethods');

Route::post('/accounting/payment-methods/store', [PaymentMethodController::class, 'store'])->name('accounting.payment_methods.store');

Route::put('/accounting/payment-methods/update/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('accounting.payment_methods.update');

// Edit route
Route::get('/payment-methods/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('accounting.edit_payment_method');

// Delete route
Route::delete('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('accounting.payment_methods.destroy');





//Accounting Transactions
Route::resource('transactions', TransactionsController::class);

Route::get('/accounting/transactions', [TransactionsController::class, 'index'])->name('transactions.index');

Route::get('/accounting/transactions/transationdetail-{id}', [TransactionsController::class, 'show'])->name('transactions.show');

Route::get('/transactions/{transaction}/print', [TransactionsController::class, 'print'])->name('transaction.print');

Route::get('/transaction-details/{id}', [TransactionsController::class, 'showTransactionDetails']);


//User Management
Route::get('admin/user-management/user-accounts', [UserManagementController::class, 'index'])->name('user.management');

Route::get('/admin/user-management/users', [UserManagementController::class, 'index'])->name('user_management');


Route::get('/admin/user-management/create', [UserManagementController::class, 'create'])->name('user.create');

Route::post('/admin/users/store', [UserManagementController::class, 'store'])->name('user.store');

Route::get('/admin/user-management/users/{id}/edit', [UserManagementController::class, 'edit'])->name('user.edit');

Route::put('/user/{user}', [UserManagementController::class, 'update'])->name('user.update');

Route::delete('/user/{id}', [UserManagementController::class, 'destroy'])->name('user.destroy');


//registrar account
Route::get('/admin/registrar-staff-accounts', [UserManagementController::class, 'registrarStaff'])->name('registrar_staff');

Route::get('/admin/registrar-staff', [UserManagementController::class, 'registrarStaff'])->name('registrar.staff');

Route::get('/admin/registrar-staff/create', [UserManagementController::class, 'createRegistrarStaff'])->name('registrar.staff.create');

Route::post('/admin/registrar-staff/store', [UserManagementController::class, 'storeRegistrarStaff'])->name('registrar.staff.store');

Route::get('/admin/registrar-staff/{id}/edit', [UserManagementController::class, 'editRegistrarStaff'])->name('registrar.staff.edit');

Route::put('/admin/registrar-staff/{id}', [UserManagementController::class, 'updateRegistrarStaff'])->name('registrar.staff.update');

Route::delete('/admin/registrar-staff/{id}', [UserManagementController::class, 'destroyRegistrarStaff'])->name('registrar.staff.destroy');




//accounting account
Route::get('/admin/accounting-staff-accounts', [UserManagementController::class, 'accountingStaff'])->name('accounting_staff');

Route::get('/admin/accounting-staff', [UserManagementController::class, 'accountingStaff'])->name('accounting.staff');

Route::get('/admin/accounting-staff/create', [UserManagementController::class, 'createAccountingStaff'])->name('accounting.staff.create');

Route::post('/admin/accounting-staff/store', [UserManagementController::class, 'storeAccountingStaff'])->name('accounting.staff.store');

Route::get('/admin/accounting-staff/{id}/edit', [UserManagementController::class, 'editAccountingStaff'])->name('accounting.staff.edit');

Route::put('/admin/accounting-staff/{id}', [UserManagementController::class, 'updateAccountingStaff'])->name('accounting.staff.update');

Route::delete('/admin/accounting-staff/{id}', [UserManagementController::class, 'destroyAccountingStaff'])->name('accounting.staff.destroy');








//accounting account
Route::get('/admin/admin-account', [UserManagementController::class, 'adminAccount'])->name('admin.account');

Route::get('/admin/admin-account', [UserManagementController::class, 'adminAccount'])->name('admin.account');

Route::get('/admin/admin-account/create', [UserManagementController::class, 'createAdmin'])->name('admin.create');

Route::post('/admin/admin-account/store', [UserManagementController::class, 'storeAdmin'])->name('admin.store');

Route::get('/admin/admin-account/{id}/edit', [UserManagementController::class, 'editAdmin'])->name('admin.edit');

Route::put('/admin/admin-account/{id}', [UserManagementController::class, 'updateAdmin'])->name('admin.update');

Route::delete('/admin/admin-account/{id}', [UserManagementController::class, 'destroyAdmin'])->name('admin.destroy');





// Profile Edit Route
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth:staff');

// Profile Update Route
Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth:staff');

// Password Update Route
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update')->middleware('auth:staff');

// Account Deletion Route
Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware('auth:staff');



require __DIR__ . '/auth.php';
