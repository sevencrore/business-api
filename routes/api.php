<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\BusinessController;
use App\Http\Controllers\API\BusinessBuyerController;
   
Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});
         

// Define route for showing the form to create a new product
Route::get('business/create', [BusinessController::class, 'create'])->name('business.create');

// Route::middleware('auth:sanctum')->group( function () {
    
Route::get('/bussiness', [BusinessController::class, 'index'])->name('bussiness.paginate');
Route::get('business', [BusinessController::class, 'index'])->name('business.index');



// Define route for storing a newly created product
Route::post('business', [BusinessController::class, 'store'])->name('business.store');

// Define route for displaying a specific product
Route::get('business/{business}', [BusinessController::class, 'show'])->name('business.show');

// Define route for showing the form to edit a specific product
Route::get('business/{business}/edit', [BusinessController::class, 'edit'])->name('business.edit');
Route::post('business/{business}/edit', [BusinessController::class, 'edit'])->name('business.edit');
Route::delete('business/delete/{id}', [BusinessController::class, 'destroy'])->name('business.destroy');

// Define route for updating a specific product
Route::put('business/{business}', [BusinessController::class, 'update'])->name('business.update');

// Define route for deleting a specific product
//Route::delete('business/{business}', [BusinessController::class, 'destroy'])->name('business.destroy');



//Route::delete('business/{business}', [BusinessController::class, 'destroy'])->name('business.destroy');
// Route::post('business/{business}/restore', [BusinessController::class, 'restore'])->name('business.restore');
// Route::delete('business/{business}/force-delete', [BusinessController::class, 'forceDelete'])->name('business.forceDelete');
// Route::get('business/trashed', [BusinessController::class, 'trashed'])->name('business.trashed');

Route::post('business/delete-multiple', [BusinessController::class, 'deleteMultiple']);
Route::post('business/restore-multiple', [BusinessController::class, 'restoreMultiple']);
Route::post('business/force-delete-multiple', [BusinessController::class, 'forceDeleteMultiple']);
Route::post('business/trashed-multiple', [BusinessController::class, 'trashedMultiple']);



Route::get('businessbuyers/business/{business_id}', [BusinessBuyerController::class, 'getByBusinessId']);


// });

Route::prefix('business-buyers')->group(function() {

    // Display a listing of business buyers (pagination)
    Route::get('/', [BusinessBuyerController::class, 'index']);

    // Store a newly created business buyer
    Route::post('/', [BusinessBuyerController::class, 'store']);

    // Display a specific business buyer by ID
    Route::get('{id}', [BusinessBuyerController::class, 'show']);

    // Update a specific business buyer by ID
    Route::put('{id}', [BusinessBuyerController::class, 'update']);

    // Delete a specific business buyer by ID
    Route::delete('{id}', [BusinessBuyerController::class, 'destroy']);

    // Delete multiple business buyers
    Route::post('delete-multiple', [BusinessBuyerController::class, 'deleteMultiple']);

    // Restore multiple business buyers (for soft deletes)
    Route::post('restore-multiple', [BusinessBuyerController::class, 'restoreMultiple']);

    // Force delete multiple business buyers (permanently)
    Route::post('force-delete-multiple', [BusinessBuyerController::class, 'forceDeleteMultiple']);

    // Retrieve trashed business buyers (soft-deleted)
    Route::post('trashed-multiple', [BusinessBuyerController::class, 'trashedMultiple']);
});

