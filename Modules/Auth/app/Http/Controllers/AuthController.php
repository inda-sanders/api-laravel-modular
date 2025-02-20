<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\UserManagement\Models\departement;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    // Register User
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|regex:/^\S*$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'entry_date' => 'date_format:Y-m-d',
            'departement_id' => 'numeric',
            'list_role' => 'array',
            'is_active' => 'integer|in:0,1,2',
        ]);

        if ($validator->fails()) {
            // return response()->json($validator->errors(), 400);
            return response()->json(['responseCode' => 400, 'message' => 'Bad Request', 'data' => $validator->errors()], 200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'entry_date' => $request->entry_date,
            'departement_id' => $request->departement_id
        ]);

        foreach ($request->list_role as $value) {
            $role = Role::findById($value)->first();
            $user->assignRole($role->name);
        }

        $token = $user->createToken('LaravelPassport')->accessToken;

        $user->token = $token;

        return response()->json(['responseCode' => 201, 'message' => 'The user has successfully registered', 'data' => $user], 200);
    }

    // Login User
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            // dd($user)
            $token = $user->createToken('client')->accessToken;
            $return = $user->get_user_data();
            // $return->put('token', $token);
            $return['token'] = $token;
            return response()->json(['responseCode' => 200, 'message' => 'The user has successfully loggedin', 'data' => $return], 200);
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

    //generate app-key for client
    public function generateAppKey(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            // return response()->json($validator->errors(), 400);
            return response()->json(['responseCode' => 400, 'message' => 'Bad Request', 'data' => $validator->errors()], 200);
        }
        $app_key = Str::random(32);
        $client = new Client();
        $client->name = $request->name;
        $client->redirect = 'http://127.0.0.1:8000';
        $client->personal_access_client = 1;
        $client->password_client = 0;
        $client->revoked = 1;
        $client->app_key = $app_key;
        $client->save();

        return response()->json(['responseCode' => 201, 'message' => 'App Key generated', 'data' => ['app_key' => $app_key]], 200);
    }
}
