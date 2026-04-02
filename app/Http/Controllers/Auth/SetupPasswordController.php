<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class SetupPasswordController extends Controller
{
    // Called on page load to verify the link is still valid
    public function verify(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if (!Password::broker()->tokenExists($user, $request->token)) {
            return response()->json(['message' => 'Link is invalid or expired'], 422);
        }

        return response()->json(['message' => 'Token valid']);
    }

    // Called on form submit
    public function setup(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if (!Password::broker()->tokenExists($user, $request->token)) {
            return response()->json(['message' => 'Link is invalid or expired'], 422);
        }

        $user->update([
            'password'         => Hash::make($request->password),
            'password_changed' => true,
        ]);

        Password::broker()->deleteToken($user);

        $sanctumToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $sanctumToken,
            'user'  => $user,
        ]);
    }
}
