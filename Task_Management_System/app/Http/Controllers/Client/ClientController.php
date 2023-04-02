<?php

namespace App\Http\Controllers\Client;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\validator;

class ClientController extends Controller
{
    public function client_create(Request $request)
    {


        $validator = validator::make($request->all(), [
            'name' => 'required|unique:clients,name',
            'address' => 'required',
            'phone' => 'required',
            'remarks' => 'required'

        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 403);
        }

        // $find_project = Project::with('client')->find($request->client_id);

        // $tt = $find_project->client;

        // foreach ($tt as $key => $value) {
        //     if ($value->name == $request->name) {
        //         return response()->json("Task Already Assisgn");
        //     }
        // }

        $client = new Client();
        $client->name = $request->name;
        $client->address = $request->address;
        $client->phone = $request->phone;
        $client->remarks = $request->remarks;
        $client->save();

        $data = [
            'status' => true,
            'message' => 'Client Created successfully',
            'data' => $client,
        ];

        return response()->json($data, 201);
    }

    public function client_list(Request $request)
    {
        $client = Client::all();
        $data = [
            'status' => true,
            'message' => "Here are all clients:",
            'data' => $client
        ];

        return response()->json($data, 201);
    }

    public function client_update(Request $request)
    {
        $id = $request->id;
        $client = Client::find($id);
        if ($client) {
            $client->name = $request->name;
            $client->address = $request->address;
            $client->phone = $request->phone;
            $client->remarks = $request->remarks;
            $client->isActive = $request->isActive;
            $client->save();

            $data = [
                "status" => true,
                "message" => 'The client is successfull Updated',
                "data" => $client
            ];
            return response()->json($data, 200);
        }
    }

    public function client_delete(Request $request)
    {
        $id = $request->input('id');

        $file = new Client();
        $file = Client::where('id', $id)->delete();
        if ($file) {
            return response()->json([
                'status' => true,
                'message' => 'This client is successfully deleted',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'This client is not deleted',

            ], 404);
        }
    }
}
