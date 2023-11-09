<?php

use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LineController;
use App\Http\Controllers\Api\ZaloController;
use App\Http\Controllers\Api\ChatInterfaceController;
use App\Http\Controllers\Api\FacebookFanpageController;
use App\Http\Controllers\Api\MessengerController;
use App\Http\Controllers\Api\StripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'project'], function () {
        Route::get('', [ProjectController::class, 'index'])->name('api.projects.list');
        Route::get('/{userId}', [ProjectController::class, 'indexByUser'])->name('api.projects.list-by-user');
        Route::post('/create', [ProjectController::class, 'createProject'])->name('api.project.create');
        Route::put('/update/{id}', [ProjectController::class, 'updateProject'])->name('api.project.update');
        Route::delete('/{id}', [ProjectController::class, 'deleteProject'])->name('api.project.delete');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users.list');
        Route::get('/{id}', [UserController::class, 'detail'])->name('users.detail');
        Route::post('/card/update', [UserController::class, 'updateCard'])->name('api.card.update');
        Route::post('/card/delete', [UserController::class, 'deleteCard'])->name('api.card.delete');
    });

    Route::group(['prefix' => 'line'], function () {
        Route::post('/create', [LineController::class, 'create'])->name('api.line.create');
    });

    Route::group(['prefix' => 'zalo'], function () {
        Route::post('/create', [ZaloController::class, 'create'])->name('api.zalo.create');
    });

    Route::group(['prefix' => 'facebook-fanpage'], function () {
        Route::post('/create', [FacebookFanpageController::class, 'create'])->name('api.facebook-fanpage.create');
    });

    Route::group(['prefix' => 'chat_interface'], function () {
        Route::post('/create', [ChatInterfaceController::class, 'create'])->name('api.chat_interface.create');
        Route::get('/{projectId}', [ChatInterfaceController::class, 'indexByProject'])->name('api.chat_interface.detail_by_project');
    });

    Route::post('webhook', [StripeController::class, 'handleWebhook']);
    Route::post('line/webhook', [LineController::class, 'handleWebhook']);
    Route::post('zalo/webhook', [ZaloController::class, 'handleWebhook'])->name('api.zalo.webhook');
    Route::get('messenger/webhook/{id}', [MessengerController::class, 'verify'])->name('api.messenger.webhook-verify');
    Route::post('messenger/webhook/{id}', [MessengerController::class, 'handleWebhook'])->name('messenger.webhook-handle');

    /**
     * chat api
     */
    Route::group(['middleware' => ['check_exist_project', 'check_message_limit']], function () {
        Route::post('chat', [ChatController::class, 'chat'])->name('api.openai.chat-bot');
        Route::post('chat-stream', [ChatController::class, 'chatStream'])->name('api.chat-stream');
    });

    /**
     * get proxy crawl
     */
    Route::get('/proxy', function () {
        $url = request('url');
        if (!$url) {
            return response()->json(['error' => 'Missing URL in the request']);
        }
        $response = Http::get($url);
        return response($response->body(), $response->status())
            ->header('Content-Type', 'text/html');
    })->name('api.proxy');
});
