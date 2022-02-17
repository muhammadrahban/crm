<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class usercontroller extends Controller
{
    public $successStatus = 200;
    public $errorStatus = 401;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'                      =>  'required|max:255|unique:users,name',
            'password'                  =>  'required|confirmed',
            'password_confirmation'     =>  'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }

        $input                      =   $request->all();
        $input['status']            =   '1';
        $input['is_admin']          =   '1';
        $input['password']          =   Hash::make($input['password']);
        $user                       =   User::create($input);
        $success['token']           =   $user->createToken('MyApp')->accessToken;
        $success['user']            =   $user;

        return response()->json(['success'=>$success], $this->successStatus);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'              =>  'required',
            'password'          =>  'required',
            'device_token'      =>  'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        $user = User::where('name', $request->name)->first();
        if($user)
        {
            if(Hash::check($request->password, $user['password']))
            {
                if($user->status == 0)
                {
                    return response()->json(['failed' => 'User not active'], $this->errorStatus);
                }
                else{
                    $user->update(['device_token' => $request->device_token]);
                    $success['token'] = $user->createToken('MyApp')->accessToken;
                    $success['user']  = $user;
                    return response()->json(['success' => $success], $this->successStatus);
                }
            }
            return response()->json(['error' => 'Invalid password'], $this->errorStatus);
        }
        else{
            return response()->json(['error' => 'This username does not exist'], $this->errorStatus);
        }
    }

    public function logout(Request $request)
    {
        DB::table('oauth_access_tokens')
        ->where('user_id', $request->user_id)
        ->update([
            'revoked' => true
        ]);
        return response()->json([
            'message' => 'Successfully logged out'
        ], $this->successStatus);
    }

}
