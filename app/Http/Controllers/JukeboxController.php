<?php

namespace App\Http\Controllers;

use App\Models\Jukebox;
use App\Models\Coin;
use App\Models\Ticket;
use App\Models\UserSongQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JukeboxController extends Controller
{
    public function showUserQueue()
    {
        $user = Auth::user();

        // Fetch current song queues for the logged-in user
        $songQueues = UserSongQueue::where('user_id', $user->user_id)
            ->with('song')
            ->get();

        return response()->json($songQueues, 200);
    }

    public function addCoins(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'amount' => 'required|integer|min:1',
        ]);

        $coin = Coin::updateOrCreate(
            ['user_id' => $request->user_id],
            ['amount' => DB::raw("amount + {$request->amount}")]
        );

        return response()->json($coin, 200);
    }

    public function queueSongs(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'song_ids' => 'required|array|min:1|max:5',
            'song_ids.*' => 'exists:songs,song_id',
        ]);

        $user = $request->user_id;
        $coin = Coin::where('user_id', $user)->first();

        // Ensure the user has at least 1 coin to queue songs
        if ($coin && $coin->amount >= 1) {
            // Queue up to 5 songs with 1 coin
            foreach ($request->song_ids as $songId) {
                UserSongQueue::create([
                    'user_id' => $request->user_id,
                    'song_id' => $songId,
                    'status' => 'queued', // Set status to queued
                ]);
            }

            $coin->amount -= 1;
            $coin->save();

            return response()->json(['message' => 'Songs added to queue'], 200);
        }

        return response()->json(['message' => 'Insufficient coins'], 400);
    }


    public function playSong(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'song_id' => 'required|exists:songs,song_id',
        ]);

        $user = $request->user_id;
        $songId = $request->song_id;

        // Check if there's a currently playing song and mark it as played (or delete it)
        $currentlyPlayingSong = UserSongQueue::where('user_id', $user)
            ->where('status', 'playing')
            ->first();

        if ($currentlyPlayingSong) {
            $currentlyPlayingSong->delete();
        }

        // Set the new song as playing
        $userSongQueue = UserSongQueue::where('user_id', $user)
            ->where('song_id', $songId)
            ->where('status', 'queued')
            ->first();

        if ($userSongQueue) {
            $userSongQueue->status = 'playing';
            $userSongQueue->save();


            // Handle ticket logic
            $ticket = Ticket::firstOrCreate(
                ['user_id' => $request->user_id],
                ['no_of_tickets' => 0]
            );

            $ticket->no_of_tickets += 1;
            $ticket->save();

            // Check if the user has enough tickets to convert to a coin
            if ($ticket->no_of_tickets >= 5) {
                $ticket->no_of_tickets -= 5;
                $ticket->save();

                Coin::updateOrCreate(
                    ['user_id' => $request->user_id],
                    ['amount' => DB::raw("amount + 1")]
                );

                return response()->json(['message' => 'Ticket converted to coin'], 200);
            }

            return response()->json(['message' => 'Song played, ticket earned'], 200);
        }

        return response()->json(['message' => 'Song not in queue'], 404);
    }

    // Method to get the current song playing
    public function currentSongPlaying(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
        ]);

        $user = $request->user_id;

        $currentSong = UserSongQueue::where('user_id', $user)
            ->where('status', 'playing')
            ->with('song')
            ->first();

        if ($currentSong) {
            return response()->json([
                'user_id' => $currentSong->user_id,
                'song' => $currentSong->song,
            ], 200);
        }

        return response()->json(['message' => 'No song currently playing at this queue number'], 404);
    }
}
