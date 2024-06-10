<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use App\Models\Coin;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AuthController extends Controller
{
    use HttpResponses;

    public function register(StoreUserRequest $request)
    {
        $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function login(LoginUserRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $this->assignLoginCoins($user);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Tokens revoked'], 200);
    }

    protected function assignLoginCoins(User $user)
    {
        $now = Carbon::now();
        $lastLogin = $user->last_login_at ? Carbon::parse($user->last_login_at) : null;

        if (!$lastLogin || $lastLogin->diffInDays($now) >= 1) {
            $coinsToAdd = $lastLogin ? 1 : 3;

            Coin::updateOrCreate(
                ['user_id' => $user->user_id],
                ['amount' => DB::raw("amount + $coinsToAdd")]
            );

            $user->last_login_at = $now;
            $user->save();
        }
    }
}
