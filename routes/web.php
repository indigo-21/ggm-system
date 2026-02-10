<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AccountLevelController;
use App\Http\Controllers\ModuleController;

use App\Http\Controllers\CemeteryController;
use App\Http\Controllers\BurialSocietyOrganizationController;
use App\Http\Controllers\GraveSpaceController;
use App\Http\Controllers\LetterTypeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\BasedLedgerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderPaymentController;
use App\Http\Controllers\OrderTypeController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Configuration 
    Route::resource('user', UserController::class);
    Route::resource('location', LocationController::class);
    Route::resource('account_level', AccountLevelController::class);
    Route::resource('module', ModuleController::class);

    // Masterfile
    Route::resource('cemetery', CemeteryController::class);
    Route::resource('burial_society_organization', BurialSocietyOrganizationController::class);
    Route::resource('grave_space', GraveSpaceController::class);
    Route::resource('letter_type', LetterTypeController::class);
    Route::resource('material', MaterialController::class);
    Route::resource('accessory', AccessoryController::class);
    Route::resource('based_ledger', BasedLedgerController::class);
    Route::resource('order_type', OrderTypeController::class);

    // Module
    Route::resource('customer', CustomerController::class);
    Route::resource('quote', QuoteController::class);

    // GROUPING QUOTE
    Route::group(['prefix'=> 'quote', 'as' => 'quote.'], function(){
        Route::post('/upsert_order_instruction_note', [QuoteController::class, 'upsertOrderInstructionNote'])
            ->name('upsert_order_instruction_note');
    });

    // GROUPING ORDER PAYMENT
    Route::group(['prefix'=> 'order_payment', 'as' => 'order_payment.'], function(){
        Route::post('/order_payment_upsert', [OrderPaymentController::class, 'upsert'])->name('order_payment_upsert');
        Route::post('/order_payment_destroy', [OrderPaymentController::class, 'destroy'])->name('order_payment_destroy');
        Route::get('/order_payment_print_receipt/{id}', [OrderPaymentController::class, 'print_receipt'])->name('order_payment_print_receipt');
    });

    

});

require __DIR__.'/auth.php';
