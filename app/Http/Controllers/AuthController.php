<?php

namespace App\Http\Controllers;

use App\Actions\User\CreateUserAction;
use App\Actions\User\LoginUserAction;
use App\Actions\User\LogoutUserAction;
use App\Http\Requests\Auth\CreateUserRequest;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(CreateUserRequest $request)
    {
        $data = $request->validated();

        $user = CreateUserAction::run($data);

        return response()->json([
            'user' => $user
        ], 201);
    }

    public function login(LoginUserRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Incorrect credentials'
            ], 401);
        }

        $token = LoginUserAction::run($user);

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        LogoutUserAction::run(auth()->user());

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
