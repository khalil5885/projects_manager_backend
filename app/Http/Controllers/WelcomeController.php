<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function welcome()
    {
        return response()->json([
            'message' => 'welcome khalil',
            'status' => 'success'
        ]);
    }
}
