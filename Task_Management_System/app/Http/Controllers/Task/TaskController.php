<?php

namespace App\Http\Controllers\Task;

use App\Models\Tas;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\TaskAssign;
use Illuminate\Http\Request;
use App\Models\ProjectAssign;
use Ramsey\Uuid\Type\Integer;
use App\Models\TaskAttachment;
use Illuminate\Support\Carbon;
use PhpParser\Node\Expr\Assign;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function task_create(Request $request)
    {
        $validator = validator::make($request->all(), [
            'project_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'end_date' => 'required',

        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 403);
        }


        $id = $request->user()->id;

        // $find_project=Project::find( $request->project_id)->task;
        // return response()->json([
        // $find_project
        // ]);

        $find_project = Project::with('task')->find($request->project_id);

        $tt = $find_project->task;

        foreach ($tt as $key => $value) {
            if ($value->name == $request->name) {
                return response()->json("Task Already Assisgn");
            }
        }

        $project = new Task();
        $project->user_id = $id;
        $project->project_id = $request->project_id;
        $project->name = $request->name;
        $project->description = $request->description;
        //$project->status = $request->status;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->save();

        $data = [
            'status' => true,
            'message' => 'Task already created',
            'data' => $project,
        ];

        return response()->json($data, 201);
    }

    public function task_assigned(Request $request)
    {
        $user_ids = $request->input('user_ids');
        foreach ($user_ids as $user_id) {
            $user = User::find($user_id);
            if (!$user || !$user->isActive) {
                return response()->json(["message" => "User is not active"]);
            } else {
                $task_assign = new TaskAssign();
                $task_assign->user_id = $user_id;
                $task_assign->task_id = $request->task_id;
                $task_assign->date = $request->date;
                $task_assign->save();
                $task_assigns[] = $task_assign;
            }
        }

        foreach ($task_assigns as $task_assign) {
            $assignedTasks[] = [
                'task_name' => $task_assign->task->name,
                'user_name' => $task_assign->user->first_name . ' ' . $task_assign->user->last_name
            ];
        }

        $data = [
            'status' => true,
            'message' => "Tasks are:",
            'data' => $assignedTasks
        ];

        return response()->json($data, 201);
    }


    public function file_create(Request $request)
    {
        $validator = validator::make($request->all(), [
            'task_id' => 'required',
            'file' => 'required'

        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 403);
        }

        $id = $request->user()->id;
        $files = new TaskAttachment();
        $files->user_id = $id;
        $files->file = $request->file('file')->store('apiDocs');
        $files->task_id = $request->task_id;
        $files->save();

        $data = [
            'status' => true,
            'message' => 'File upload successfully',
            'data' => $files,
        ];


        return response()->json($data, 201);
    }

    public function delete_file(Request $request)
    {
        $id = $request->input('id');

        $file = new TaskAttachment();
        $file = TaskAttachment::where('id', $id)->delete();
        if ($file) {
            $data = [
                'status' => true,
                'message' => 'This file is successfully deleted',
                'data' => []
            ];

            return response()->json($data, 200);
        } else {
            $data = [
                'status' => false,
                'message' => 'This file is not deleted',
                'data' => []
            ];

            return response()->json([
                'status' => false,
                'message' => 'This file is not deleted',

            ], 404);
        }
    }

    public function task_update(Request $request)
    {
        $id = $request->id;
        $task = Task::find($id);
        if ($task) {
            $task->project_id = $request->project_id;
            $task->name = $request->name;
            $task->description = $request->description;
            $task->status = $request->status;
            $task->start_date = $request->start_date;
            $task->end_date = $request->end_date;
            $task->save();

            $data = [
                "status" => true,
                "message" => 'The task is successfull Updated',
                "data" => $task
            ];
            return response()->json($data, 200);
        }
    }

    public function task_delete(Request $request)
    {
        $id = $request->input('id');

        $file = new Task();
        $file = Task::where('id', $id)->delete();
        if ($file) {
            return response()->json([
                'status' => true,
                'message' => 'This task is successfully deleted',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'This task is not deleted',

            ], 404);
        }
    }

    public function task_assigned_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required',
            'task_id' => 'required',
            'date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 403);
        }

        $user_ids = $request->input('user_ids');
        foreach ($user_ids as $user_id) {
            $user = User::find($user_id);
            if (!$user || !$user->isActive) {
                return response()->json(["message" => "User is not active"]);
            }
        }

        $id = $request->id;
        $task_assign = TaskAssign::find($id);
        $task_assign->user_id = $user_id;
        $task_assign->task_id = $request->task_id;
        $task_assign->date = $request->date;
        $task_assign->save();


        $assignedProjects[] = $task_assign;
        foreach ($assignedProjects as $user) {
            $assignedUsers[] = [
                'Task Name' => $task_assign->task->name,
                'User name' => $user->user->first_name . ' ' . $user->user->last_name
            ];
        }

        $data = [
            'status' => true,
            'message' => "Assigned are:",
            'data' => $assignedUsers
        ];

        return response()->json($data, 200);
    }

    public function list_task()
    {
        $task = Task::all();
        if ($task) {
            $data = [
                "status" => true,
                "message" => "Here are all tasks",
                "data" => $task
            ];
            return response()->json($data);
        } else {
            $data = [
                "status" => false,
                "message" => "Task not found",
                "data" => []
            ];
            return response()->json($data);
        }
    }

    public function task_with_project()
    {
        $task = Task::with('project')->get();
        $data = [
            'status' => true,
            'message' => 'Tasks with projects:',
            'data' => $task,
        ];
        return response()->json($data);
    }

    public function task_count()
    {
        $total_task = Task::count();
        $complete_task = Task::where('status', 'Finished')->count();
        $incompleted_task = Task::where('status', 'Pending')->count();
        $overdue_task = Task::where('status', 'Onhold')->count();

        $data = [
            'status' => true,
            'message' => 'Here are all counts',
            'data' => [
                'Total task' => $total_task,
                'Complete task' => $complete_task,
                'In-completed task' => $incompleted_task,
                'Overdue task' => $overdue_task
            ]
        ];
        return response()->json($data);
    }

    public function task_for_specific_project(Request $request)
    {
        $project_id = $request->project_id;
        $project = Project::find($project_id)->task;
        $data = [
            'status' => true,
            'message' => 'Tasks for specific projects:',
            'data' => $project
        ];
        return response()->json($data);
    }

    public function calculate_task(Request $request)
    {
        $projects = Project::with('task')->get();
        $projectData = [];

        foreach ($projects as $project) {
            $completed_tasks = $project->task->where('status', 'Finished')->count();
            $total_tasks = $project->task->count();

            if ($total_tasks > 0) {
                $task_complete = ($completed_tasks / $total_tasks) * 100;
            } else {
                $task_complete = 0;
            }

            $projectData[] = [
                'project' => $project,
                'task complete' => Round($task_complete, 2),
            ];
        }

        $data = [
            'status' => true,
            'message' => 'Projects with tasks:',
            'data' => $projectData,
        ];

        return response()->json($data, 201);
    }

    public function today_task()
    {
        $today = Carbon::now()->format('Y-m-d');
        $today_task = Task::where('start_date', $today)->get();
        if ($today_task) {
            $data = [
                'status' => true,
                'message' => '....Todays tasks are....',
                'data' => $today_task
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => false,
                'message' => 'Task not found',
                'data' => []
            ];
            return response()->json($data, 404);
        }
    }

    public function remain_days()
    {
        $end_date = Carbon::now()->format('Y-m-d');
        return $end_date;
    }
}
