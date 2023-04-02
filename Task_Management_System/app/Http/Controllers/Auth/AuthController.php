<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function User_create(Request $request)
    {
        $validator = validator::make($request->all(), [
            'first_name' => 'required|regex:/^[A-Z a-z,]+$/|max:10',
            'last_name' => 'required|regex:/^[A-Z a-z,]+$/|max:10',
            'email' => 'required|email|max:255|unique:users',
            // 'password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'password' => 'required',
            'address' => 'required|string',
            'phone' => 'required|regex:/(01)[0-9]{9}/',
            // 'designation_id'=>'required|max:15',
            // 'department_id'=>'requred|max:20',
            'type' => 'required',
            'isActive' => 'required',
            'image' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 403);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone' => $request->phone,
            'designation_id' => $request->designation_id,
            'department_id' => $request->department_id,
            'type' => $request->type,
            'isActive' => $request->isActive,
            'image' => $request->file('image')->store('apiDocs')

        ]);


        $data = [
            'status' => 200,
            'message' => "Registration Successful",
            'data' => [
                'token' => $user->createToken('API TOKEN')->plainTextToken,
                'user name' => $user->first_name . " " . $user->last_name,
                'designation' => $user->designation->title,
                'department' => $user->department->name,
                'address' => $user->address,
                'phone' => $user->phone,
                'type' => $user->type,
                'image' => $user->image,

            ]
        ];

        return response()->json($data);
    }

    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 409);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }


            $user = User::where('email', $request->email)->where('isActive', true)->first();

            // ->with( 'designation','department')

            if (empty($user)) {
                return response()->json([
                    'status' => false,
                    'message' => "User not found or inactive"
                ], 404);
            }

            $data = [
                'status' => 200,
                'message' => 'User Logged In Successfully',
                'data' => [
                    'token' => $user->createToken('API TOKEN')->plainTextToken,
                    'status code' => 200,
                    'user name' => $user->first_name . " " . $user->last_name,
                    'designation' => $user->designation->title,
                    'department' => $user->department->name,
                    'type' => $user->type
                ]

            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(
            'Logout Successful'
        );
    }
}
