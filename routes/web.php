<?php

use App\Http\Controllers\Project\RefreshProjectPackagesController;
use App\Http\Controllers\Project\UpdateProjectController;
use App\Http\Controllers\ShowDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', ShowDashboardController::class)->name('home');

