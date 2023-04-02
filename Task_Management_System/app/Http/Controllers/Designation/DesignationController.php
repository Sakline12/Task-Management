<?php

namespace App\Http\Controllers\Designation;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    public function designation_create(Request $request)
    {

        $validator = validator::make($request->all(), [
            'title' => 'required|unique:designations,title',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 403);
        }

        $designation = new Designation();
        $designation->title = $request->title;
        $designation->save();

        if ($designation) {
            $data = [
                'status' => true,
                'message' => 'Designation Created Successfully',
                'data' => $designation
            ];

            return response()->json(
                $data,
                201
            );
        }
    }

    public function designation_update(Request $request)
    {
        $id = $request->id;
        $project = Designation::find($id);
        if ($project) {
            $project->title = $request->title;
            $project->save();

            $data = [
                'status' => true,
                'message' => 'The designation successfull Updated',
                'data' => $project
            ];
            return response()->json($data, 200);
        }
    }

    public function designation_list()
    {
        $designation = Designation::all();
        if ($designation) {
            $data = [
                'status' => true,
                'message' => 'Here are all designation',
                'data' => $designation
            ];
            return response()->json($data);
        }
    }

    public function designation_delete(Request $request)
    {
        $id = $request->id;
        $designation = Designation::where('id', $id)->delete();
        if ($designation) {
            $data = [
                'status' => true,
                'message' => 'Designation are deleted',
                'data' => $designation
            ];
            return response()->json($data, 201);
        }
    }
}
