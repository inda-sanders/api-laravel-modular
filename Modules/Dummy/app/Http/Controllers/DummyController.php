<?php

namespace Modules\Dummy\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Dummy\Models\DummyModel;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DummyController extends Controller
{

    public function __construct(Request $request)
    {
        $this->dummy = new DummyModel();
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

        $getData = $this->dummy->getAllBy($limit, $offset, $search, $col, $dir, $where);
        $countAll = $this->dummy->getCountAllBy();
        $countData = $this->dummy->getCountAllBy($search, $where);
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
        try {
            $validator = $request->validate([
                'name'  => 'required|string|max:255',
                'name' => 'required',
                'age' => 'required|numeric',
                'price' => 'required',
                'lat' => 'required',
                'is_active' => 'required',
                'description' => 'required',
                'birthdate' => 'required',
                'start_time' => 'required',
                'created_at' => 'required',
                'updated_at' => 'required',
                'amount' => 'required',
                'preferences' => 'required',
                'status' => 'required',
                'image' => 'required',
                'uuid' => 'required',
            ]);

            $data = [
                'name' => 'John Doe',
                'age' => 25,
                'price' => 99.99,
                'lat' => '40.7128',
                'is_active' => true,
                'description' => 'Lorem ipsum dolor sit amet.',
                'birthdate' => '1998-12-25',
                'start_time' => '08:00:00',
                'created_at' => now(),
                'updated_at' => now(),
                'amount' => 199.99,
                'preferences' => json_encode(['color' => 'blue', 'size' => 'L']),
                'status' => 'completed',
                'image' => file_get_contents(storage_path('favicon.ico')), // or use binary image data
                'uuid' => '61e5b30b-830d-3d19-ae9b-140ef76be7b8'
            ];

            // try {
            //  $this->dummy->create($data);
            //     return response()->json([
            //         'responseCode' => 200,
            //         'message' => 'Success insert data',
            //         'data' => $data
            //     ], 200);
            // } catch (\Throwable $th) {
            //     return response()->json([
            //         'responseCode' => 500,
            //         'message' => 'Failed to insert data: ' . $th->getMessage(),
            //         'data' => $data
            //     ], 500);
            // }
            $this->dummy->create($data);
            return ApiResponse::success('Success insert data', $data, 200);
        } catch (ValidationException $e) {
            return ApiResponse::validationError($e);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to insert data' .  $e->getMessage(), $validator, 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function getOne($id)
    {
        try {

            $getData = $this->dummy->find($id);


            return response()->json([
                'responseCode' => 200,
                'message' => 'Success get data',
                'data' => $getData,
            ], 200);
            // return 'getOne';

            $getData =  $this->dummy->where(['name' => 'Prof. Collin Lebsack'])->get();
            $countAll =  $this->dummy->count();

            $return = [
                'data' => $getData,
                'totalData' => 1,
                'countData' => 1,
                'page' => 1,
            ];


            return ApiResponse::success('Success get data', $return, 200);
        } catch (ValidationException $e) {
            return ApiResponse::validationError($e);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to update data' .  $e->getMessage(), ["id" => $id], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // $data = $request->validate([
            //     'name'  => 'required|string|max:255',
            //     'name' => 'required',
            //     'age' => 'required|numeric',
            //     'price' => 'required',
            //     'lat' => 'required',
            //     'is_active' => 'required',
            //     'description' => 'required',
            //     'birthdate' => 'required',
            //     'start_time' => 'required',
            //     'created_at' => 'required',
            //     'updated_at' => 'required',
            //     'amount' => 'required',
            //     'preferences' => 'required',
            //     'status' => 'required',
            //     'image' => 'required',
            //     'uuid' => 'required',
            // ]);

            $data = [
                'name' => 'dummy 1',
                'age' => 25,
                'price' => 99.99,
                'lat' => '40.7128',
                'is_active' => true,
                'description' => 'Lorem ipsum dolor sit amet.',
                'birthdate' => '1998-12-25',
                'start_time' => '08:00:00',
                'created_at' => now(),
                'updated_at' => now(),
                'amount' => 199.99,
                'preferences' => json_encode(['color' => 'blue', 'size' => 'L']),
                'status' => 'completed',
                'image' => file_get_contents(storage_path('favicon.ico')), // or use binary image data
                'uuid' => '61e5b30b-830d-3d19-ae9b-140ef76be7b8'
            ];

            // try {
            //      $this->dummy->where('id', $id)
            //         ->update($data);
            //     return response()->json([
            //         'responseCode' => 200,
            //         'message' => 'Success update data',
            //         'data' => $data
            //     ], 200);
            // } catch (\Throwable $th) {
            //     return response()->json([
            //         'responseCode' => 500,
            //         'message' => 'Failed to update data: ' . $th->getMessage(),
            //         'data' => $data
            //     ], 500);

            $this->dummy->where('id', $id)->update($data);
            return ApiResponse::success('Success update data', $data, 200);
        } catch (ValidationException $e) {
            return ApiResponse::validationError($e);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to update data' .  $e->getMessage(), $request, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // $this->dummy->where('id', $id)
            $data = $id->validate([
                'id'  => 'required',
            ]);
            $this->dummy->where('id', $id)
                ->delete();
            //     return response()->json([
            //         'responseCode' => 200,
            //         'message' => 'Success delete data',
            //         'data' => ['id' => $id]
            //     ], 200);
            // } catch (\Throwable $th) {
            //     return response()->json([
            //         'responseCode' => 500,
            //         'message' => 'Failed to delete data: ' . $th->getMessage(),
            //         'data' => ['id' => $id]
            //     ], 500);
            // }
            return ApiResponse::success('Success delete data', $data, 200);
        } catch (ValidationException $e) {
            return ApiResponse::validationError($e);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to delete data' .  $e->getMessage(), ["id" => $id], 500);
        }
    }
}
