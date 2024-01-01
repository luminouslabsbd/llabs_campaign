<?php

use Illuminate\Support\Facades\Route;
use Luminouslabs\Installer\Http\Controllers\AdminDashBoardController;
use Luminouslabs\Installer\Http\Controllers\Api\LinkShareController;
use Luminouslabs\Installer\Http\Controllers\Api\LLMemberAuthAPIController;
use Luminouslabs\Installer\Http\Controllers\Api\MemberSpinHandlerController;
use Luminouslabs\Installer\Http\Controllers\CampainController;
use Luminouslabs\Installer\Http\Controllers\MemberDashBoardController;
use Luminouslabs\Installer\Http\Controllers\PartnerDashBoardController;
use Luminouslabs\Installer\Http\Controllers\RocketChatController;
use Luminouslabs\Installer\Http\Controllers\StaffDashBoardController;

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

    // Member Register & Login API
    Route::get('test', [LLMemberAuthAPIController::class, 'get']);
    Route::post('register', [LLMemberAuthAPIController::class, 'register'])->name('register')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::post('login', [LLMemberAuthAPIController::class, 'login'])->name('login')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Link Share
    Route::post('get/hash-by-tenantid', [LinkShareController::class, 'getHashByTenantID']);
    Route::get('get-whatsapp-link', [LinkShareController::class, 'whatsappLinkGenerator'])->middleware('auth:member_api');

//Member spinner Api's
    Route::post('get/spinned-rewards', [MemberSpinHandlerController::class, 'gotSpinned'])->middleware('auth:member_api');

});

Route::group(['prefix' => '{locale}/partner/ll/v1', 'middleware' => ['web'], 'as' => 'luminouslabs::'], function () {

    Route::get('campain-setup-index', [CampainController::class, 'getComapin'])->name('partner.campain.manage');
    Route::get('campain-setup-create', [CampainController::class, 'create'])->name('partner.campain.create');
    Route::post('campain-setup-storge', [CampainController::class, 'CampaignStorge'])->name('partner.campain.storge');
    Route::get('/campain-edit/{id}', [CampainController::class, 'edit'])->name('partner.campain.edit');
    Route::post('/campain-update/{id}', [CampainController::class, 'update'])->name('partner.campain.update');
    Route::get('/campain-view/{id}', [CampainController::class, 'view'])->name('partner.campain.view');
    Route::post('/campain-delete/{id}', [CampainController::class, 'delete'])->name('partner.campain.delete');
    Route::post('/campain-spiner-remove/{id}', [CampainController::class, 'campain_spiner_id_remove'])->name('partner.campain_spiner_remove');

});

//admin rocket chat setting routes
Route::group(['prefix' => '{locale}/admin', 'middleware' => ['web']], function () {

    Route::get('rocket-chat-data', [RocketChatController::class, 'index'])->name('admin.rocket_chat');
    Route::get('rocket-chat-data-edit/{id}', [RocketChatController::class, 'edit'])->name('admin.rocket_chat.edit');
    Route::post('rocket-chat-update', [RocketChatController::class, 'storeUpdate'])->name('admin.rocket_chat.update');
    Route::get('rocket-chat-add', [RocketChatController::class, 'add'])->name('admin.rocket_chat.add');

});

Route::group(['prefix' => '{locale}/api/ll/v1', 'as' => 'luminouslabs::'], function () {

    Route::get('get-campain-spiner-data', [CampainController::class, 'getSpinarData']);

});

// Admin Dashboard Data
Route::group(['prefix' => '{locale}/admin/ll/v1', 'middleware' => ['web'], 'as' => 'luminouslabs::'], function () {
    Route::get('get-dashboard-card-count', [AdminDashBoardController::class, 'getDashboardCardCount'])->name('admin.getDashboardCardCount');
    Route::get('/seven-days-data', [AdminDashBoardController::class, 'getLastSevenDaysData'])->name('admin.getLastSevenDaysData');
});

// Partner Dashboard Data
Route::group(['prefix' => '{locale}/partner/ll/v1', 'middleware' => ['web'], 'as' => 'luminouslabs::'], function () {
    Route::get('get-dashboard-card-count', [PartnerDashBoardController::class, 'getDashboardCardCount'])->name('partner.getDashboardCardCount');
    Route::get('/seven-days-data', [PartnerDashBoardController::class, 'getLastSevenDaysData'])->name('partner.getLastSevenDaysData');
});

// Member Dashboard Data
Route::group(['prefix' => '{locale}/member/ll/v1', 'middleware' => ['web'], 'as' => 'luminouslabs::'], function () {
    Route::get('get-dashboard-card-count', [MemberDashBoardController::class, 'getDashboardCardCount'])->name('member.getDashboardCardCount');
    Route::get('/seven-days-data', [MemberDashBoardController::class, 'getLastSevenDaysData'])->name('member.getLastSevenDaysData');
});

// Staff
Route::group(['prefix' => '{locale}/staff/ll/v1', 'middleware' => ['web'], 'as' => 'luminouslabs::'], function () {
    Route::get('get-dashboard-card-count', [StaffDashBoardController::class, 'getDashboardCardCount'])->name('staff.getDashboardCardCount');
});
