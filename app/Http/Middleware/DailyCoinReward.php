<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Coin;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DailyCoinReward
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            $now = Carbon::now();
            $lastLogin = $user->last_login_at ? Carbon::parse($user->last_login_at) : null;

            if (!$lastLogin || $lastLogin->diffInDays($now) >= 1) {
                $coinsToAdd = $lastLogin ? 1 : 3;

                Coin::updateOrCreate(
                    ['user_id' => $user->user_id],
                    ['amount' => DB::raw("amount + $coinsToAdd")]
                );

                $user->last_login_at = $now;

                // Debugging statement
                logger()->info('User object before save', ['user' => $user]);

                $user->save();
            }
        }

        return $next($request);
    }
}
