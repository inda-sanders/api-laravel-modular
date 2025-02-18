<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Register User
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            // return response()->json($validator->errors(), 400);
            return response()->json(['responseCode' => 400, 'message' => 'Bad Request', 'data' => $validator->errors()], 200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('LaravelPassport')->accessToken;

        $user->token = $token;

        return response()->json(['responseCode' => 201, 'message' => 'The user has successfully registered', 'data' => $user], 200);
    }

    // Login User
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('client')->accessToken;

            $user->token = $token;
            return response()->json(['responseCode' => 200, 'message' => 'The user has successfully loggedin', 'data' => $user], 200);
        } else {
            return response()->json(['responseCode' => 401, 'message' => 'wrong login credentials', 'data' => ['email' => $request->email]], 200);
        }
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['responseCode' => 205, 'message' => 'Logout successful, please refresh', 'data' => []], 200);
    }
}
