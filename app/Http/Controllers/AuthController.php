<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;  // Import the User modeluse Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use HttpResponses;

    public function login()
    {
        return 'This is my login method';
    }


    public function register(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|confirmed|min:8', // 'confirmed' ensures password and password_confirmation match
        ]);

        // Create the user if validation passes
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        // Optionally, return a response or a token for the user
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function logout()
    {
        return response()->json('You have successfully logged out');
    }
}
