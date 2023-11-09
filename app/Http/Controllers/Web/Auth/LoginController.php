<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->back();
        }
        return view('auth.login');
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            $findedUser = User::where('google_id', $user->id)->first();

            if ($findedUser) {
                auth()->login($findedUser, true);
                return redirect()->route('project.list');
            } else {
                $newUser = DB::table('users')->updateOrInsert(
                    [
                        'email' => $user->email
                    ],
                    [
                        'google_id' => $user->id,
                        'name' => $user->name,
                        'email_verified_at' => Carbon::now()->toDateTimeString(),
                        'avatar' => $user->avatar,
                        'uuid' => Str::uuid(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
                if ($newUser) {
                    $findedUser = User::where('google_id', $user->id)->first();
                    auth()->login($findedUser, true);
                    return redirect()->route('project.list');
                }
            }
        } catch (Exception $e) {
            Log::debug($e);
            redirect()->to('login');
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to('/');
    }
}
