<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(Request $request)
    {
        $this->user = new user();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit') ?? 10;
        $offset = $request->get('offset') ?? 0;
        $search = $request->get('search') ?? [];
        $col = $request->get('col') ?? '';
        $dir = $request->get('dir') ?? '';
        $where = $request->get('where') ?? [];
        $page = $request->get('page') ?? 1;

        $where[] = ['client_id', '=', $request->client_id];
        $where[] = ['users.is_deleted', '!=', 1];
        $where[] = ['users.name', '!=', 'superadmin'];

        $getData = $this->user->getAllBy($limit, $offset, $search, $col, $dir, $where);
        $countAll = $this->user->getCountAllBy([], [['client_id', '=', $request->client_id], ['users.name', '!=', 'superadmin']]);
        $countData = $this->user->getCountAllBy($search, $where);
        $return = [
            'data' => $getData,
            'totalAllData' => $countAll,
            'totalData' => $countData,
            'page' => $page,
        ];
        return response()->json([
            'responseCode' => 200,
            'message' => 'Success get data',
            'data' => $return
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|regex:/^\S*$/',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->where(function ($query) use ($request) {
                if ($request->has('client_id')) {
                    $query->where([['client_id', '=', $request->client_id], ['email', '=', $request->email]]);
                } else {
                    return response()->json(['responseCode' => 500, 'message' => 'Internal error', 'data' => []], 200);
                }
            })],
            'password' => 'required|string|min:6',
            'entry_date' => 'date_format:Y-m-d',
            'departement_id' => 'numeric',
            'list_role' => 'array',
            'is_active' => 'integer|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return response()->json(['responseCode' => 400, 'message' => 'Bad Request', 'data' => $validator->errors()], 200);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'entry_date' => $request->entry_date,
            'departement_id' => $request->departement_id,
            'client_id' => $request->client_id,
            'created_by' => Auth::user()->id
        ];

        try {
            $data = User::create($data);

            if (isset($request->list_role)) {
                $test = [];
                foreach ($request->list_role as $value) {
                    $role = Role::find($value);
                    $data->assignRole($role->name);
                }
            }


            $token = $data->createToken($request->client)->accessToken;

            $data->token = $token;

            return response()->json(['responseCode' => 201, 'message' => 'The user has successfully created', 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'responseCode' => 500,
                'message' => 'Failed to insert data: ' . $th->getMessage(),
                'data' => $data
            ], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function getOne(Request $request, $id)
    {
        $getData = $this->user->where([['id', '=', $id], ['client_id', '=', $request->client_id]])->first();

        if (!empty($getData)) {
            return response()->json([
                'responseCode' => 200,
                'message' => 'Success get data',
                'data' => $getData,
            ], 200);
        } else {
            return response()->json([
                'responseCode' => 400,
                'message' => 'Data Not Found',
                'data' => [],
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'username' => 'string|max:255|regex:/^\S*$/',
            'email' => ['string', 'email', 'max:255', Rule::unique('users')->where(function ($query) use ($request) {
                if ($request->has('client_id')) {
                    $query->where([['client_id', '=', $request->client_id], ['email', '=', $request->email]]);
                } else {
                    return response()->json(['responseCode' => 500, 'message' => 'Internal error', 'data' => []], 200);
                }
            })],
            'entry_date' => 'date_format:Y-m-d',
            'departement_id' => 'numeric',
            'list_role' => 'array',
            'is_active' => 'integer|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return response()->json(['responseCode' => 400, 'message' => 'Bad Request', 'data' => $validator->errors()], 200);
        }

        try {
            $update = request()->only($this->user->getUpdateFillable());
            $update['updated_by'] = Auth::user()->id;
            $data = $this->user->where([['id', '=', $id], ['client_id', '=', $request->client_id]])->update($update);

            if (isset($request->list_role)) {
                $user = $this->user->find($id);
                $user->syncRoles([]);
                foreach ($request->list_role as $value) {
                    $role = Role::findById($value)->first();
                    $data->assignRole($role->name);
                }
            }

            return response()->json(['responseCode' => 201, 'message' => 'The user has successfully updated', 'data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'responseCode' => 500,
                'message' => 'Failed to update data: ' . $th->getMessage(),
                'data' => $data
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $delete = $this->user->where([['id', '=', $id], ['client_id', '=', $request->client_id]])
                ->update(['deleted_at' => date('Y-m-d H:i:s'), 'is_deleted' => 1, 'deleted_by' => Auth::user()->id]);
            if ($delete) {
                return response()->json([
                    'responseCode' => 200,
                    'message' => 'Success delete data',
                    'data' => ['id' => $id]
                ], 200);
            } else {
                return response()->json([
                    'responseCode' => 404,
                    'message' => 'Data Not Found',
                    'data' => ['id' => $id]
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'responseCode' => 500,
                'message' => 'Failed to delete data: ' . $th->getMessage(),
                'data' => ['id' => $id]
            ], 500);
        }
    }
}
