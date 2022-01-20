<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class userlistcontroller extends Controller
{
    public $successStatus = 200;
    public $errorStatus = 401;

    public function index()
    {
        $user = User::all();
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'                      =>  'required|max:255',
            'password'                  =>  'required|confirmed',
            'password_confirmation'     =>  'required',
            'device_token'              =>  'nullable',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }

        $input                      =   $request->all();
        $input['status']            =   '1';
        $input['is_admin']          =   '0';
        $input['password']          =   Hash::make($input['password']);
        $user                       =   User::create($input);
        $success['name']            =   $user->name;

        return response()->json(['success'=>$success], $this->successStatus);
    }

    public function destroyUser(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id'                      =>  'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        dd($request);
        User::find($request->id)->delete();
        $success['message'] = "delete User Successfully";

        return response()->json(['success'=>$success], $this->successStatus);
    }

    public function changeStatus(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'is_admin'   =>   'required',
            'status'     =>  'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        $input  =   $request->all();
        $user->update($input);

        return response()->json(['success'=>$user], $this->successStatus);
    }
}
