<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserSongQueueRequest;
use App\Http\Requests\UpdateUserSongQueueRequest;
use App\Models\UserSongQueue;
use Illuminate\Http\Request;

class UserSongQueueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(UserSongQueue::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserSongQueueRequest $request)
    {
        $userSongQueue = UserSongQueue::create($request->validated());

        return response()->json($userSongQueue, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserSongQueue $userSongQueue)
    {
        return response()->json($userSongQueue, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserSongQueueRequest $request, UserSongQueue $userSongQueue)
    {
        $userSongQueue->update($request->validated());

        return response()->json($userSongQueue, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserSongQueue $userSongQueue)
    {
        $userSongQueue->delete();

        return response()->json(null, 204);
    }
}

