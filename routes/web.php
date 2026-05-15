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
use App\Http\Controllers\OrderInscriptionController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderFileController;
use App\Http\Controllers\OrderMailController;
// use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    // return view('welcome');
    return view('auth/login');
});

Route::get('/migrate-fresh', function (){
	Artisan::call('migrate:fresh');
    Artisan::call('db:seed');
	dd("Migrated Fresh Buddy");
 }); 

// Route::get('/migrate', function (){
// 	Artisan::call('migrate');
// 	dd("Migrated Buddy");
//  }); 
 
//  Route::get('/seed-seeders', function (){
// 	 Artisan::call('db:seed');
// 	 dd("Seed na Buddy");
//   }); 

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
    Route::resource('order', OrderController::class);

    // GROUPING QUOTE
    Route::group(['prefix'=> 'quote', 'as' => 'quote.'], function(){
        Route::post('/upsert_order_instruction_note', [QuoteController::class, 'upsertOrderInstructionNote'])
            ->name('upsert_order_instruction_note');
        Route::post('/order_instruction_note', [QuoteController::class, 'getOrderInstructionNote'])
            ->name('order_instruction_note');
        Route::post('/index_filtered', [QuoteController::class, 'index_filtered'])
            ->name('index_filtered');
    });

    // GROUPING ORDER PAYMENT
    Route::group(['prefix'=> 'order_payment', 'as' => 'order_payment.'], function(){
        Route::post('/order_payment_upsert', [OrderPaymentController::class, 'upsert'])->name('order_payment_upsert');
        Route::post('/order_payment_destroy', [OrderPaymentController::class, 'destroy'])->name('order_payment_destroy');
        
    });

    Route::post("/order_inscription/upsert", [OrderInscriptionController::class, 'upsert'])->name('order_inscription_upsert');
    Route::post("/order_inscription/approval", [OrderInscriptionController::class, 'approval'])->name('order_inscription_approval');


    // GROUPING PDF GENERATIONS
    Route::group(['prefix'=> 'pdf', 'as' => 'pdf.'], function(){
        Route::get('/inscription/{id}', [OrderInscriptionController::class, 'printPdf'])->name('inscription');
        Route::get('/payment_receipt/{order_payment_id}', [OrderPaymentController::class, 'payment_receipt'])->name('payment_receipt');
        Route::get('/payment_statement/{order_id}', [OrderPaymentController::class, 'payment_statement'])->name('payment_statement');
        Route::get('/quotation/{order_id}', [QuoteController::class, 'print_pdf'])->name('print_pdf');
        Route::get('/order/{order_id}', [OrderController::class, 'print_pdf'])->name('print_pdf');
        Route::get('/order_no_price/{order_id}', [OrderController::class, 'print_pdf_no_price'])->name('print_pdf_no_price');
    });

    // ORDER MAIL
    Route::post("/send_email", [OrderMailController::class, 'send_email'])->name('send_email');

    // Route::group(['prefix' => 'files', 'as' => 'files'], function(){
        
    // });
    Route::post('upload_files', [OrderFileController::class, 'store'])->name("upload_files");    
    Route::post('delete_file', [OrderFileController::class, 'destroy'])->name("delete_file");    
    Route::post('file_is_email', [OrderFileController::class, 'update'])->name("file_is_email");    
    

});

require __DIR__.'/auth.php';
