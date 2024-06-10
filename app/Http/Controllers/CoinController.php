<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoinRequest;
use App\Http\Requests\UpdateCoinRequest;
use App\Http\Requests\GiveCoinsRequest;
use App\Models\Coin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Coin::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCoinRequest $request)
    {
        $coin = Coin::create($request->validated());

        return response()->json($coin, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Coin $coin)
    {
        return response()->json($coin, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCoinRequest $request, Coin $coin)
    {

        $coin->update($request->validated());

        return response()->json($coin, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coin $coin)
    {
        $coin->delete();

        return response()->json(null, 204);
    }

    // Custom method to give coins to a user
    public function giveCoins(GiveCoinsRequest $request)
    {
        $coin = Coin::updateOrCreate(
            ['user_id' => $request->user_id],
            ['amount' => DB::raw("amount + {$request->amount}")]
        );

        return response()->json($coin, 200);
    }

    // Method to view the coin status of the authenticated user
    public function viewCoinStatus()
    {
        $user = Auth::user();

        // Fetch the coin status for the authenticated user
        $coinStatus = Coin::where('user_id', $user->user_id)->first();

        if ($coinStatus) {
            return response()->json($coinStatus, 200);
        }

        return response()->json(['message' => 'Coin status not found'], 404);
    }
}
