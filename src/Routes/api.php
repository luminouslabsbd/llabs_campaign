<?php

use Illuminate\Support\Facades\Route;
use Luminouslabs\Installer\Http\Controllers\Api\LinkShareController;
use Luminouslabs\Installer\Http\Controllers\Api\LLMemberAuthController;
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
    Route::get('get-whatsapp-link', [LinkShareController::class, 'QrGenerator']);
    //Get all data after scan
    Route::get('scaned/{hash_id}', [LinkShareController::class, 'QrCodeScaned'])->name('qr-scaned');

    // Return additional data
    Route::get('/user-campaign-qr-data', [LinkShareController::class, 'userCampaignQrData']);

    //Member spinner Api's
    Route::post('get/spinned-rewards', [MemberSpinHandlerController::class, 'gotSpinned'])->middleware('auth:member_api');

    //Update Member Reward counting
    Route::get('/update-spinned-rewards/{id}',[LinkShareController::class, 'updateSpinnedRewards']);
    //Get Member All Winning Rewards
    Route::post('/get-winning-rewards',[LinkShareController::class, 'getWinningRewards']);

});
