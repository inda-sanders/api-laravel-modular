<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\EmailExist;
use App\Rules\RegexRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\UserManagement\Models\departement;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Email;
use Laravel\Passport\Token;
use Ramsey\Uuid\Uuid;

class AuthController extends Controller
{
    /**
     * Register user
     */
    public function register(Request $request)
    {
        try {


            $validator = $request->validate([
                'name' => ['required', new RegexRules("double_spacing")],
                'username' => 'required|string|max:255|regex:/^\S*$/',
                'email' => ['required', 'string', 'email', 'max:255', new EmailExist($request->client_id)],
                'password' => 'required|string|min:6',
                'entry_date' => 'date_format:Y-m-d',
                'departement_id' => 'numeric',
                'list_role' => 'array',
                'is_active' => 'integer|in:0,1,2',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'entry_date' => $request->entry_date,
                'departement_id' => $request->departement_id,
                'client_id' => $request->client_id
            ]);

            if (isset($request->list_role) && !empty($request->list_role)) {
                foreach ($request->list_role as $value) {
                    $role = Role::findById($value)->first();
                    $user->assignRole($role->name);
                }
            }

            $token = $user->createToken('LaravelPassport')->accessToken;
            $user->token = $token;

            $user = User::create($request);

            return ApiResponse::success('The user has successfully registered', $user, 201);
        } catch (ValidationException $e) {
            return ApiResponse::validationError($e);
        } catch (\Exception $e) {
            return ApiResponse::error('Something went wrong', $e->getMessage(), 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password,'client_id'=>$request->client_id])) {
            $user = Auth::user();
            $client = Client::where(['name' => $request->client])->first();
            $personalAccessToken = $user->createToken($request->client);
            $token = $personalAccessToken->token;
            $token->client_id = $client->id;
            $token->save();

            $token = $personalAccessToken->accessToken;
            $return = $user->get_user_data();
            $return->put('token', $token);
            $return['token'] = $token;
            return response()->json(['responseCode' => 200, 'message' => 'The user has successfully loggedin', 'data' => $return], 200);
        } else {
            return response()->json(['responseCode' => 401, 'message' => 'wrong login credentials', 'data' => ['email' => $request->email]], 200);
        }

        // Jika berhasil login, buat token
        $token = $user->createToken('API Token')->accessToken;
        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['responseCode' => 205, 'message' => 'Logout successful, please refresh', 'data' => []], 200);
    }

    /**
     * generate app-key for client
     */
    public function generateAppKey(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['responseCode' => 400, 'message' => 'Bad Request', 'data' => $validator->errors()], 200);
        }
        $api_key = Str::random(32);
        $client = new Client();
        $client->name = $request->name;
        $client->secret =  Str::random(40);
        $client->redirect = 'http://127.0.0.1:8000';
        $client->personal_access_client = 1;
        $client->password_client = 0;
        $client->revoked = 0;
        $client->api_key = $api_key;
        $client->save();

        return response()->json(['responseCode' => 201, 'message' => 'App Key generated', 'data' => ['api_key' => $api_key]], 200);
    }
}
