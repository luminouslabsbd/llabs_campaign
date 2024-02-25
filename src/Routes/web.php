<?php

use Illuminate\Support\Facades\Route;
use Luminouslabs\Installer\Http\Controllers\AdminDashBoardController;
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

Route::group(['prefix' => '{locale}/partner/ll/v1', 'middleware' => ['web'], 'as' => 'luminouslabs::'], function () {

    Route::get('campain-setup-index', [CampainController::class, 'getComapin'])->name('partner.campain.manage');
    Route::get('cards-manage', [CampainController::class, 'cardsManage'])->name('partner.cards.manage');
    Route::post('store-card', [CampainController::class, 'storeCard'])->name('partner.card.store');
    Route::get('campain-setup-create', [CampainController::class, 'create'])->name('partner.campain.create');
    Route::post('campain-setup-storge', [CampainController::class, 'store'])->name('partner.campain.storge');
    //Route::post('campain-setup-storge', [CampainController::class, 'CampaignStorge'])->name('partner.campain.storge');
    Route::get('/campain-edit/{id}', [CampainController::class, 'edit'])->name('partner.campain.edit');
    Route::post('/campain-update/{id}', [CampainController::class, 'update'])->name('partner.campain.update');
    Route::get('/campain-view/{id}', [CampainController::class, 'view'])->name('partner.campain.view');
    Route::get('/campain-winners/{id}',[CampainController::class, 'campainWinners'])->name('partner.campain.winners');
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
