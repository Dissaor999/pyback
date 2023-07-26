<?php
use App\Http\Controllers as C;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/getZohoCode', [C\join\zoho\ZohoConnectionController::class,'getCodezoho']);
Route::get('/zohoCode', [C\join\zoho\ZohoConnectionController::class,'codeResponse']);
Route::get('/refreshTokenZoho', [C\join\zoho\ZohoConnectionController::class,'refreshTokenZoho']);
