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

    // GROUPING A ROUTE
    // Route::group(['prefix'=> 'customer', 'as' => 'customer.'], function(){
    // });


});

require __DIR__.'/auth.php';
