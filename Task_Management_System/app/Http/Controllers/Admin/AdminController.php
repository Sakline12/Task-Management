<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function user_list(Request $request)
    {
        // $id = $request->user()->id;
        // $user = User::find($id);

        // if (!$user || !$user->isActive) {

        //     $data = [
        //         'status' => false,
        //         'message' => 'Your are inactive',
        //         'data' => [],
        //     ];


        //     return response()->json($data, 404);
        // }
        $all_users = User::all();

        $data = [
            'status' => true,
            'message' => 'Users are:',
            'data' => $all_users,
        ];
        return response()->json($data, 404);
    }

    public function user_delete(Request $request)
    {
        // $id = $request->user()->id;
        // $user = User::find($id);

        // if (!$user || !$user->isActive) {
        //     $data = [
        //         'status' => false,
        //         'message' => 'Your are inactive',
        //         'data' => [],
        //     ];


        //     return response()->json($data, 404);
        // }

        $id = $request->id;
        $user = User::findOrFail($id);
        $user = $user->delete();
        if ($user) {
            $data = [
                'status' => true,
                'message' => 'User is deleted',
                'data' => []
            ];
            return response()->json($data);
        }
    }
}
