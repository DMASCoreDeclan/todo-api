<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;  // Import the User modeluse Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only(['email', 'password']))) {
        return $this->error('', 'Credentials do not match', 401);
        }

        $user = User::where('email', $request->email)->first();
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->email . ' ' . $user->name)->plainTextToken
        ]);
    }


    public function register(StoreUserRequest $request)
    {
        // Validate the incoming request
        $validated = $request->validated([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|confirmed|min:8', // 'confirmed' ensures password and password_confirmation match
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->email . ' ' . $user->name)->plainTextToken
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return $this->success([
            'message' => 'You have logged out.'
        ]);
    }
}
