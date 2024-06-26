<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AccountController, DealController};

/**
 *  Api Router
 *
 *
 * @see http://127.0.0.1/api/v2/zoho/get_accounts
 * @see http://127.0.0.1/api/v2/zoho/get_deal_stages
 * @see http://127.0.0.1/api/v2/zoho/store_account
 * @see http://127.0.0.1/api/v2/zoho/store_deal
 */

Route::prefix('/v2')->group(function () {
    Route::get('/zoho/get_accounts', [AccountController::class, 'getAccounts']);
    Route::get('/zoho/get_deal_stages', [DealController::class, 'getDealStages']);
    Route::post('/zoho/store_account', [AccountController::class, 'storeAccount']);
    Route::post('/zoho/store_deal', [DealController::class, 'storeDeal']);
});