<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\customer;
use App\Models\item;
use App\Models\itemactivivty;
use App\Models\order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class userlistcontroller extends Controller
{
    public $successStatus = 200;
    public $errorStatus = 401;

    public function index()
    {
        $user = User::all();
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function show($id)
    {
        $user = User::find($id);
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
            'is_admin'   =>  'required',
            'status'     =>  'required',
            'name'       =>  'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        $input  =   $request->only('is_admin', 'status', 'name');
        if ($request->has('password') && ($request->password !== NULL)) {
            $input['password']  = Hash::make($request->password);
        }
        // return $input;
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
        !empty($input['actual_quantity']) ? $input['actual_quantity'] : $input['actual_quantity'] = "0";
        $item->update($input);

        $activityInsert = [
            'item_id'               => $item->id,
            'order_id'              => $item->order_id,
            'user_id'               => $request->user_id,
            'status'                => $request->status,
            'quantity'              => NULL,
            'is_back'               => NULL,
            'user_check'            => NULL,
        ];
        $activity   = itemactivivty::create($activityInsert);

        $all_items = item::where('order_id', $item->order_id)->get();
        return response()->json(['success' => $all_items], $this->successStatus);
    }

    public function customerlist(Request $request){

        $first      = $request->first_date;
        $second     = $request->second_date;
        $name     = $request->customer_name;

        $customer = customer::withCount('order')
        ->when(!empty($name), function($q) use ($name){
            return $q->where('name', 'LIKE', '%'.$name.'%');
        })
        ->when(!empty($first) && !empty($second), function($q) use ($first, $second){
            return $q->whereHas('order', function($qq) use ($first, $second){
                return $qq->whereBetween(DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d'))"), [$first, $second]);
            });
        })
        ->when(!empty($first) && empty($second), function($query) use ($first){
            return $query->whereHas('order', function($qq) use ($first){
                return $qq->where('created_at', 'LIKE', '%'.$first.'%');
            });
        })
        ->when(!empty($second) && empty($first), function($query) use ($second){
            return $query->whereHas('order', function($qq) use ($second){
                return $query->where('created_at', 'LIKE', '%'.$second.'%');
            });
        })
        ->latest()
        ->get();
        return response()->json(['success' => $customer], $this->successStatus);
    }

    public function customerorder($id){
        // $customer = $customer->with('order')->get();
        // return response()->json(['success' => $customer], $this->successStatus);

        $order = order::with(['items.product', 'cargo', 'activity.user', 'customer', 'user'])->where('customer_id', $id)->latest()->get();
        return response()->json(['success' => $order], $this->successStatus);
    }

    public function get_itemActivity(order $order){
        $all_items = itemactivivty::with('user', 'item')->where('order_id', $order->id)->latest()->get();
        return response()->json(['success' => $all_items], $this->successStatus);
    }
}
