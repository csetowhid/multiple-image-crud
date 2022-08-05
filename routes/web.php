<?php

use App\Http\Controllers\MultiImageController;
use App\Models\MultiImage;
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
    $data['images'] = MultiImage::get();
    return view('welcome',$data);
});

Route::post('image/store',[MultiImageController::class,'store'])->name('image.store');

Route::get('image/remove/{image}/{id}',[MultiImageController::class,'remove'])->name('image.remove');

Route::get('image/edit/{id}',[MultiImageController::class,'edit'])->name('image.edit');

Route::post('image/update/{id}',[MultiImageController::class,'update'])->name('image.update');

Route::prefix('{lang?}')->middleware('locale')->group(function() {

    Route::get('me', function () {
        dd("asce");
    });
});