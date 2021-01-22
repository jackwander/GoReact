<?php

use App\Http\Livewire\Dashboard;
use App\Http\Livewire\ShowFiles;
use App\Http\Livewire\UploadFile;
use App\Http\Livewire\ViewFile;
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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware'=>['auth']],function ($router) {
  $controller = 'App\Http\Controllers\Controller';

  Route::get('dashboard', Dashboard::class)->name('dashboard');
  Route::get('upload', UploadFile::class)->name('upload');
  Route::get('files', ShowFiles::class);
  Route::get('/view/{file_id}', ViewFile::class)->name('view');
});

require __DIR__.'/auth.php';
