<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Mail\WelcomeUserMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return response()->json([
            'status' => 'success',
            'data'   => $users,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|min:2|max:255',
            'email'       => 'required|email|unique:users',
            'global_role' => 'required|in:admin,employee,client',
        ]);

        $password = Str::random(12);

        $user = DB::transaction(fn() => User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'global_role' => $request->global_role,
            'password'    => Hash::make($password),
        ]));

        Mail::to($user->email)->send(new WelcomeUserMail($user, $password));

        return response()->json([
            'status' => 'success',
            'data'   => $user,
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json(['status' => 'success', 'data' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'        => 'required|string|min:2|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'global_role' => 'required|in:admin,employee,client',
        ]);

        $user->update($request->only('name', 'email', 'global_role'));

        return response()->json(['status' => 'success', 'data' => $user]);
    }

    public function destroy(User $user)
    {
        $currentUser = auth::user();
        if ($currentUser->global_role !== "admin") {
            return response()->json([
                'message' => 'Unauthorized. Only administrators can delete accounts.'
            ], 403);
        }
        if ($user->global_role === "admin") {
            return response()->json([
                'message' => 'You cannot delete another administrator.'
            ], 403);
        }
        $user->delete();
        return response()->json(['status' => 'success', 'message' => 'User deleted.']);
    }
}
