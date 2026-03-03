<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

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
Route::get('/display', [AdminController::class, 'display'])->name('display');

// Route untuk Panel Admin
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::post('/admin/update', [AdminController::class, 'update'])->name('admin.update');
Route::post('/monitor-ping', function (Request $request) {
    Setting::updateOrCreate(
        ['key' => 'last_online'],
        ['value' => Carbon::now()->toDateTimeString()]
    );
    return response()->json(['status' => 'success']);
})->name('monitor.ping');

Route::get('/monitor-status-check', function () {
    $setting = \App\Models\Setting::where('key', 'last_online')->first();
    $lastOnline = $setting ? \Carbon\Carbon::parse($setting->value) : null;
    
    // Anggap online jika ping terakhir kurang dari 90 detik yang lalu
    $isOnline = $lastOnline && $lastOnline->diffInSeconds(now()) <= 90;
    
    return response()->json(['is_online' => $isOnline]);
});