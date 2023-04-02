<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function department_create(Request $request)
    {

        $validator = validator::make($request->all(), [
            'name' => 'required|unique:departments,name',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 403);
        }

        $department = new Department();
        $department->name = $request->name;
        $department->save();

        if ($department) {
            $data = [
                'status' => true,
                'message' => 'Department Created Successfully',
                'data' => $department
            ];

            return response()->json(
                $data,
                201
            );
        }
    }

    public function department_update(Request $request)
    {
        $id = $request->id;
        $project = Department::find($id);
        if ($project) {
            $project->name = $request->name;
            $project->save();

            $data = [
                'status' => true,
                'message' => 'The department is successfull Updated',
                'data' => $project
            ];

            return response()->json($data, 200);
        }
    }

    public function department_list()
    {
        $department = Department::all();
        if ($department) {
            $data = [
                'status' => true,
                'message' => 'Here are all departments:',
                'data' => $department
            ];
            return response()->json($data, 201);
        }
    }

    public function department_delete(Request $request)
    {
        $id = $request->id;
        $department = Department::where('id', $id)->delete();
        if ($department) {
            $data = [
                'status' => true,
                'message' => 'Despartment is deleted',
                'data' => $department
            ];
            return response()->json($data, 201);
        }
    }
}
