<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseCategoryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


//expense_categories
Route::get('/expense/categories', [ExpenseCategoryController::class, 'get_expense_categories']);
Route::post('/expense/categories', [ExpenseCategoryController::class, 'create_expense_category']);
Route::put('/expense/categories/{category_id}', [ExpenseCategoryController::class, 'update_expense_category']);
Route::get('/expense/categories/{category_id}', [ExpenseCategoryController::class,'get_single_expense_category_record_by_id']);