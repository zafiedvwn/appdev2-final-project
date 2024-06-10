<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSongRequest;
use App\Http\Requests\UpdateSongRequest;
use App\Models\Song;
use Illuminate\Http\Request;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Song::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSongRequest $request)
    {
        $song = Song::create($request->validated());

        return response()->json($song, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Song $song)
    {
        return response()->json($song, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSongRequest $request, Song $song)
    {
        $song->update($request->validated());

        return response()->json($song, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Song $song)
    {
        $song->delete();

        return response()->json(null, 204);
    }
}
