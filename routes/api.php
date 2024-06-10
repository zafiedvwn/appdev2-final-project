<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksController;
use App\Models\User;

use App\Http\Controllers\SongController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSongQueueController;
use App\Http\Controllers\CoinController;
use App\Http\Controllers\JukeboxController;
use App\Http\Controllers\TicketController;

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

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Jukebox
Route::middleware(['auth:sanctum', 'daily.coin.reward'])->group(function () {
    Route::get('jukebox/user/song-queues', [JukeboxController::class, 'showUserQueue']);
    Route::post('jukebox/add-coins', [JukeboxController::class, 'addCoins']);
    Route::post('jukebox/queue-song', [JukeboxController::class, 'queueSong']); // For queuing one song at a time
    Route::post('jukebox/queue-songs', [JukeboxController::class, 'queueSongs']); // For queuing multiple songs
    Route::post('jukebox/play-song', [JukeboxController::class, 'playSong']);
    Route::post('jukebox/current-song', [JukeboxController::class, 'currentSongPlaying']);
});

// User commands
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/songs', SongController::class);
    Route::get('/coins/status', [CoinController::class, 'viewCoinStatus']);
    Route::get('/tickets/status', [TicketController::class, 'viewTicketStatus']);
});

// Viewing commands
Route::get('/users', [UserController::class, 'index']);

// Admin commands
Route::post('/coins/give', [CoinController::class, 'giveCoins']);

