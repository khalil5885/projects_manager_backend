<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeUserMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'role' => 'required|string|lowercase|in:employee,customer',
        ]);
        $password=Str::password(12);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role'=>$request->role,
            'password' => Hash::make($password),
            
        ]);
        Mail::to('kablouti.fady01@gmail.com')->queue(new WelcomeUserMail($user, $password));

        

        // Auth::login($user);

       
        return response()->json(['message' => 'Success'], 201);

    }
}
