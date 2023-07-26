<?php

use App\Http\Controllers as C;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    //:::::::::::::::::::::::::::::::::::::: Login :::::::::::::::::::::::::::::::::::::::
    Route::post('user/login', [C\user\AuthController::class, 'login']);
    //:::::::::::::::::::::::::::::::::::::: Auth Middleware API ::::::::::::::::::::::::::::::::::::::
    Route::middleware('auth:api')->group(function () {
        //:::::::::::::::::::::::::::::::::::::: Test ::::::::::::::::::::::::::::::::::::::
        Route::get('test', function () {
            return response()->json(['status' => true], 200);
        });
        //:::::::::::::::::::::::::::::::::::::: User ::::::::::::::::::::::::::::::::::::::
        Route::post('user/update-permission', [C\user\AuthController::class, 'updatePermission']);
        Route::get('user/get-permission', [C\user\AuthController::class, 'getPermission']);
        Route::apiResource('user', C\user\UserController::class);
        //:::::::::::::::::::::::::::::::::::::: Sales ::::::::::::::::::::::::::::::::::::::
        Route::prefix('sale')->group(function () {
            Route::get('export',[C\sale\SaleOrderController::class,'export']);
            Route::apiResource('channel', C\sale\ChannelController::class);
            Route::apiResource('payment-type', C\sale\PaymentTypeController::class);
            Route::apiResource('client', C\sale\ClientController::class);
            Route::apiResource('order', C\sale\SaleOrderController::class);
        });
        //:::::::::::::::::::::::::::::::::::::: Inventory ::::::::::::::::::::::::::::::::::::::
        Route::prefix('inventory')->group(function () {
            Route::apiResource('item', C\inventory\ItemController::class);
        });
        //:::::::::::::::::::::::::::::::::::::: Catalogue ::::::::::::::::::::::::::::::::::::::
        Route::prefix('catalogue')->group(function () {
            # Route::apiResource('key', C\catalogue\KeyController::class);
        });
        //:::::::::::::::::::::::::::::::::::::: Invoice ::::::::::::::::::::::::::::::::::::::
        Route::prefix('invoice')->group(function () {
            Route::apiResource('local', C\invoice\InvoiceLocalController::class);
            Route::get('invoiced', [C\invoice\InvoiceLocalController::class, 'invoiced']);
        });
        //:::::::::::::::::::::::::::::::::::::: Join Apps ::::::::::::::::::::::::::::::::::::::
        Route::prefix('join')->group(function () {
            Route::prefix('shopify')->controller(C\join\shopify\ShopifyOrderController::class)
                ->group(function () {
                    Route::get('getOrders', 'getOrders');
                });
            Route::prefix('zoho')->controller(C\join\zoho\ZohoItemController::class)
                ->group(function () {
                    Route::get('getZohoItems', 'getZohoItems');
                });
            Route::prefix('listo')->controller(C\join\listo\InvoiceController::class)
                ->group(function () {
                    Route::get('listoGetXml', 'listoGetXml');
                });
        });
    });
});
