<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\item;
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

    public function destroyUser($id)
    {
        User::find($id)->delete();
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

    public function listItems()
    {
        $product = product::all();
        return response()->json(['success'=>$product], $this->successStatus);
    }

    public function singleItem($id)
    {
        $cat_product = product::find($id);
        $product = product::where('cat_id', $cat_product->cat_id)->get();
        return response()->json(['success'=>$product], $this->successStatus);
    }

    public function updateitem(Request $request, item $item)
    {
        $validator = Validator::make($request->all(), [
            'status'    => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        $input = $request->all();
        $item->update($input);
        $all_items = item::where('order_id', $item->order_id)->get();
        return response()->json(['success' => $all_items], $this->successStatus);
    }
}
