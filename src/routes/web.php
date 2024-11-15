<?php


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

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth')->group(function () {
    // Route::get('Admin/index', Dotgix\Chatsupport\app\Livewire\Admin\Index::class)->name('Admin.index');
    // Route::get('Admin/chat/{query}', Dotgix\Chatsupport\app\Livewire\Admin\Chat::class)->name('Admin.chat');
    // Route::get('Consultant/index', Dotgix\Chatsupport\app\Livewire\Consultant\Index::class)->name('Consultant.index');
    // Route::get('Consultant/chat/{query}', Dotgix\Chatsupport\app\Livewire\Consultant\Chat::class)->name('Consultant.chat');
});
Route::get('Check/', function(){dd(phpinfo());})->name('Consultant.chat');
