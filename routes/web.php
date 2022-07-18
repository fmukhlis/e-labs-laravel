<?php

use App\Http\Controllers\PendaftaranController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\DataCollector\AjaxDataCollector;

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
    return view('home', [
        "title" => "Home",
    ]);
});

// REGULAR ROUTE
Route::get('/pendaftaran', [PendaftaranController::class, 'index']);
Route::get('/pendaftaran/order', [PendaftaranController::class, 'orderNew']);
Route::get('/pendaftaran/redirect/{pasien:no_rm}', [PendaftaranController::class, 'redOrder']);
Route::get('/pendaftaran/{periksa:no_lab}/order', [PendaftaranController::class, 'order']);

Route::post('/pendaftaran/order', [PendaftaranController::class, 'store']);
Route::put('/pendaftaran/{periksa:no_lab}/order', [PendaftaranController::class, 'update']);
Route::delete('/pendaftaran/{periksa:no_lab}/order', [PendaftaranController::class, 'destroy']);

// AJAX ROUTE

// Live Search
Route::get('/pendaftaran/func/searchResTable', [PendaftaranController::class, 'searchResTable'])->name('pendaftaran.func.searchResTable');
Route::get('/pendaftaran/func/getPatientData', [PendaftaranController::class, 'getPatientData'])->name('pendaftaran.func.getPatientData');
Route::get('/pendaftaran/func/getDoctorData', [PendaftaranController::class, 'getDoctorData'])->name('pendaftaran.func.getDoctorData');

// Manage Doctor Model / Database
Route::post('/pendaftaran/manageDoctor', [PendaftaranController::class, 'storeDoctor']);
Route::get('/pendaftaran/manageDoctor', [PendaftaranController::class, 'displayDoctor']);
Route::post('/pendaftaran/{dokter:kode}/manageDoctor', [PendaftaranController::class, 'updateDoctor']);
Route::delete('/pendaftaran/{dokter:kode}/manageDoctor', [PendaftaranController::class, 'destroyDoctor']);

// Manage (Pemeriksaan)<->(Periksa) Model / Database
Route::put('/pendaftaran/{periksa:no_lab}/syncOrderTest', [PendaftaranController::class, 'syncOrderTest']);
Route::get('/pendaftaran/{periksa:no_lab}/syncOrderTest', [PendaftaranController::class, 'displayOrderTest']);


// Route::get('/pendaftaran/func/getAndSelect', [PendaftaranController::class, 'getAndSelect'])->name('pendaftaran.func.getAndSelect');
