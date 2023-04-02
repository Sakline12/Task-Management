<?php

namespace App\Http\Controllers\Comment;

use App\Models\Task;
use App\Models\User;
use App\Models\Comment;
use App\Models\TaskAssign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;

class CommentController extends Controller
{
    public function create_comment(request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required',
            'comment_box' => 'required',
            'date' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 403);
        }

        $id = $request->user()->id;

        $user_ids = $request->input('user_ids');
        foreach ($user_ids as $assign_id) {
            $user = User::find($assign_id);
            if (!$user || !$user->isActive) {
                return response()->json(["message" => "User is not active"]);
            } else {
                $comment = new Comment();
                $comment->user_id = $id;
                $comment->task_id = $request->task_id;
                $comment->assign_id = $assign_id;
                $comment->comment_box = $request->comment_box;
                $comment->date = $request->date;
                $comment->save();
            }
        }

        $data = [
            'status' => true,
            'message' => 'Comment successfully created',
            'data' => [],
        ];

        return response()->json($data, 201);
    }

    public function comment_list()
    {
        $comment = Comment::all();
        if ($comment) {
            $data = [
                'status' => true,
                'message' => "Here are all comments:",
                'data' => $comment
            ];
            return response()->json($data);
        }
    }

    public function comment_update(Request $request)
    {

        $user_id = $request->user()->id;
        $user = $request->user();
        if ($user) {
            $id = $request->id;
            $comment = Comment::find($id);
            $comment->user_id = $user_id;
            $comment->task_id = $request->task_id;
            $comment->comment_box = $request->comment_box;
            $comment->date = $request->date;
            $comment->save();

            $data = [
                "status" => true,
                "message" => 'The comment is successfull Updated',
                "data" => $comment
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "status" => false,
                "message" => 'The comment is not successfull Updated',
                "data" => []
            ];
            return response()->json($data, 404);
        }
    }

    public function comment_delete(Request $request)
    {
        $id = $request->id;
        $comment = Comment::where('id', $id)->delete();
        if ($comment) {
            $data = [
                'status' => true,
                'message' => 'Comment is deleted',
                'data' => $comment
            ];
            return response()->json($data, 201);
        } else {
            $data = [
                'status' => false,
                'message' => 'Comment is not deleted',
                'data' => []
            ];
            return response()->json($data, 404);
        }
    }

    public function comments_for_specific_task(Request $request)
    {
        $task_id = $request->task_id;
        $comment = Task::find($task_id)->comment;
        $data = [
            'status' => true,
            'message' => 'Comment for specific task:',
            'data' => $comment
        ];
        return response()->json($data, 201);
    }

    public function my_Comment(Request $request)
    {
        $assign_id = $request->user()->id;
        $comment = User::find($assign_id)->comment_assign;
        $data = [
            'status' => true,
            'message' => "Your comment is:",
            'data' => $comment


        ];
        return response()->json($data, 201);
    }
}
