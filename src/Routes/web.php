<?php

use Illuminate\Support\Facades\Route;
use Luminouslabs\Installer\Http\Controllers\Api\LLMemberAuthAPIController;
use Luminouslabs\Installer\Http\Controllers\CampainController;
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



Route::group(['prefix' => '{locale}/ll/api/member/v1', 'as' => 'luminouslabs::'], function () {

    Route::get('test', [LLMemberAuthAPIController::class, 'get']);
    Route::post('register', [LLMemberAuthAPIController::class, 'register'])->name('register')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::post('login', [LLMemberAuthAPIController::class, 'login'])->name('login')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    // Route::post('logout', [LLMemberAuthAPIController::class, 'logout'])->name('logout')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    
   
});

Route::group(['prefix' => '{locale}/partner/ll/v1', 'middleware' => ['web'] ,'as' => 'luminouslabs::' ], function () {

    Route::get('campain-setup-index', [CampainController::class, 'getComapin'])->name('partner.campain.manage');
    Route::get('campain-setup-create', [CampainController::class, 'create'])->name('partner.campain.create');
    Route::post('campain-setup-storge', [CampainController::class, 'CampaignStorge'])->name('partner.campain.storge');
    Route::get('/campain-edit/{id}', [CampainController::class, 'edit'])->name('partner.campain.edit');
    Route::post('/campain-update/{id}', [CampainController::class, 'update'])->name('partner.campain.update');
    Route::get('/campain-view/{id}', [CampainController::class, 'view'])->name('partner.campain.view');
    Route::post('/campain-delete/{id}', [CampainController::class, 'delete'])->name('partner.campain.delete');
    Route::post('/campain-spiner-remove/{id}', [CampainController::class, 'campain_spiner_id_remove'])->name('partner.campain_spiner_remove');

});

Route::group(['prefix' => '{locale}/api/ll/v1' ,'as' => 'luminouslabs::' ], function () {

    Route::get('get-campain-spiner-data', [CampainController::class, 'getSpinarData']);
    
});





// Route::prefix('api/v1/ll')->group(function () {

    // Link Share 
    // Route::post('get/hash-by-tenantid', [App\Http\Controllers\Api\LinkShareController::class, 'getHashByTenantID']);
    // Route::get('get-whatsapp-link', [App\Http\Controllers\Api\LinkShareController::class, 'whatsappLinkGenerator']);
       
// });

