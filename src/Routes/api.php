<?php

use Illuminate\Support\Facades\Route;
use Luminouslabs\Installer\Http\Controllers\Api\LinkShareController;
use Luminouslabs\Installer\Http\Controllers\Api\MemberSpinHandlerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::prefix('{locale}/v1/ll')->group(function () {
    Route::prefix('member')->group(function () {

        Route::post('login', [LLMemberAuthController::class, 'login']);
        Route::post('register', [LLMemberAuthController::class, 'register']);

    });
// Link Share
    Route::post('get/hash-by-tenantid', [LinkShareController::class, 'getHashByTenantID']);
    Route::get('get-whatsapp-link', [LinkShareController::class, 'whatsappLinkGenerator'])->middleware('auth:member_api');

//Member spinner Api's
    Route::post('get/spinned-rewards', [MemberSpinHandlerController::class, 'gotSpinned'])->middleware('auth:member_api');

});
