<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeUserMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
class UserController extends Controller
{
    /**
     * Display the registration view.
     */


    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */





    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'global_role' => 'required|string|lowercase|in:employee,client',
        ]);
        $password = Str::password(12);

        $user = User::create([
            'global_role' => $request->global_role,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);
        // Mail::to($user->email)->queue(new WelcomeUserMail($user, $password));
        Mail::to($user->email)->send(new WelcomeUserMail($user, $password));

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');

    }



    public function index()
    {
        $users = User::latest()->paginate(20);
        return Inertia::render('Admin/UserIndex', [
            'users' => $users->values()->toArray(),
        ]);
    }



    public function create()
    {
        return Inertia::render('Admin/UserCreate');
    }

}
