<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ExpenseController;


// Home → Groups list
Route::get('/', function () {
    return redirect()->route('groups.index');
});

// Groups
Route::get('/groups', [GroupController::class, 'index'])
    ->name('groups.index');

Route::get('/groups/create', [GroupController::class, 'create'])
    ->name('groups.create');
    
Route::post('/groups/{id}/update', [GroupController::class, 'update'])
    ->name('groups.update');

Route::post('/groups', [GroupController::class, 'store'])
    ->name('groups.store');

Route::get('/groups/{id}', [GroupController::class, 'show'])
    ->name('groups.show');

// Expenses
Route::get('/expenses/create/{group}', [ExpenseController::class, 'create'])
    ->name('expenses.create');

Route::post('/expenses', [ExpenseController::class, 'store'])
    ->name('expenses.store');

Route::post('/groups/{group}/members', [GroupController::class, 'addMember'])
    ->name('groups.members.add');

    Route::post('/members/{id}/update', [GroupController::class, 'updateMember'])
    ->name('groups.members.update');

    
    Route::post('/groups/{group}/quick-split', [ExpenseController::class, 'quickSplit'])
    ->name('groups.quickSplit');

    Route::post('/expenses/{id}/update', [ExpenseController::class, 'update'])
    ->name('expenses.update');

 // Edit Quick Total Split (show form)
Route::get(
    '/expenses/{expense}/quick-split/edit',
    [ExpenseController::class, 'editQuickSplit']
)->name('expenses.quickSplit.edit');;

// Update Quick Total Split
Route::post(
    '/expenses/{expense}/quick-split/update',
    [ExpenseController::class, 'updateQuickSplit']
)->name('groups.quickSplit.update');




Route::post('/members/{id}/delete', [GroupController::class, 'deleteMember'])
    ->name('groups.members.delete');

Route::post('/expenses/{id}/delete', [ExpenseController::class, 'delete'])
    ->name('expenses.delete');

Route::post('/groups/{id}/delete', [GroupController::class, 'delete'])
    ->name('groups.delete');



