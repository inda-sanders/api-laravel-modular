<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Auth\Models\departmentModel;

class DepartmentController extends Controller
{
    public function __construct(Request $request)
    {
        $this->department = new departmentModel();
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

        $where[] = ['is_deleted', '!=', 1];

        $getData = $this->department->getAllBy($limit, $offset, $search, $col, $dir, $where);
        $countAll = $this->department->getCountAllBy();
        $countData = $this->department->getCountAllBy($search, $where);
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
            'name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            // return response()->json($validator->errors(), 400);
            return response()->json(['responseCode' => 400, 'message' => 'Bad Request', 'data' => $validator->errors()], 200);
        }
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::user()->id,
        ];
        try {
            $this->department->create($data);
            return response()->json([
                'responseCode' => 200,
                'message' => 'Success insert data',
                'data' => $data
            ], 200);
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
    public function getOne($id)
    {
        $getData = $this->department->where([['id', '=', $id], ['is_deleted', '!=', 1]])->first();

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
            'name' => 'string|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json(['responseCode' => 400, 'message' => 'Bad Request', 'data' => $validator->errors()], 200);
        }
        try {

            $update = request()->only($this->department->getUpdateFillable());
            $update['updated_by'] = Auth::user()->id;
            $data = $this->department->where('id', $id)
                ->update($update);
            return response()->json([
                'responseCode' => 200,
                'message' => 'Success update data',
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'responseCode' => 500,
                'message' => 'Failed to update data: ' . $th->getMessage(),
                'data' => ['id' => $id]
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $delete = $this->department->where('id', $id)
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
