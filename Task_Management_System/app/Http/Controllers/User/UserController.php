<?php

namespace App\Http\Controllers\User;

use App\Models\Task;
use App\Models\Project;
use App\Models\TaskAssign;
use Illuminate\Http\Request;
use App\Models\ProjectAssign;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function my_project(Request $request)
    {
        $user_id = $request->user()->id;
        $project = ProjectAssign::where('user_id', $user_id)->with('project')->get();
        if ($project) {
            $data = [
                'status' => true,
                'message' => 'Your project is:',
                'data' => $project
            ];
            return response()->json($data, 201);
        }
    }

    public function my_task(Request $request)
    {
        $user_id = $request->user()->id;
        $task = TaskAssign::where('user_id', $user_id)->with('task')->get();
        return response()->json($task);
    }

    public function project_status_update(Request $request)
    {

        $id = $request->id;
        $data = Project::find($id);
        $data->status = $request->status;
        $data->save();
        return response()->json(
            "Status Update Sucessfully"
        );
    }

    public function task_status_update(Request $request)
    {

        $id = $request->id;
        $data = Task::find($id);
        $data->status = $request->status;
        $data->save();
        return response()->json(
            "Status Update Sucessfully"
        );
    }
}
