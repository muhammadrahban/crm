<?php

namespace App\Http\Controllers\api;

use App\Models\item;
use App\Models\User;
use App\Models\order;
use App\Models\activity;
use App\Models\customer;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public $successStatus = 200;
    public $errorStatus = 401;

    /**
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = order::with(['items.product', 'cargo', 'customer', 'activity.user', 'user'])
        ->orderBy('id', 'desc')->get();
        return response()->json(['success' => $order], $this->successStatus);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'type'              => 'required',
            'no_item'           => 'required',
            'total_no_item'     => 'required',
            'customer_id'       => 'required',
            'user_id'           => 'required',
            'cargo_id'          => 'required',
            'order_status'      => 'required',
            'items'             => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }

        $input                 = $request->all();
        $customers = customer::where('name', $request->customer_id)->first();
        if ($customers) {
            $input['customer_id'] = $customers->id;
        }else{
            $customer = customer::create([
                'name'     =>  $request->customer_id
            ]);
            $input['customer_id'] = $customer->id;
        }

        $unique_no = order::orderBy('id', 'DESC')->pluck('id')->first();
        if($unique_no == null or $unique_no == ""){
            $unique_no = 1;
        }
        else{
            $unique_no = $unique_no + 1;
        }
        $input['order_ticket'] = "H". str_pad($unique_no, 4, "0", STR_PAD_LEFT);
        $orders                = order::create($input);
        $int = 0;
        $items = [];
        $itemss = json_decode($request->items);
        foreach($itemss as $key => $item){
            // return $item->name;
            $data = [
                'order_id'          => $orders->id,
                'name'              => $item->name,
                'quantity'          => $item->quantity,
                'actual_quantity'   => $item->actual_quantity,
                'detail'            => $item->detail,
                'status'            => $item->status,
            ];
            $items[]  =   item::create($data);
            $int++;
        }

        $activityInsert = [
            'order_id'              => $orders->id,
            'user_id'               => $request->user_id,
            'status'                => $request->order_status,
        ];
        $activity   =   activity::create($activityInsert);
        $order = order::with(['items.product', 'cargo', 'activity.user', 'user'])->where('id', $orders->id)->get();
        $success = [
            'order' => $order,
        ];

        $user_name   = User::find($request->user_id);
        if ($request->type == 1) {
            $message = "New Order Created From ".$user_name->name;
            $notification = new Notification;
            $from       =  'HP Plus';
            $users = User::all();
            $notification->toMultiDevice($users, $from,$message,null,null);
        }

        return response()->json(['success'=>$success], $this->successStatus);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = order::with(['items.product', 'cargo', 'activity.user', 'customer', 'user'])->where('id', $id)->get();
        return response()->json(['success' => $order], $this->successStatus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = order::with(['items.product', 'cargo', 'activity.user', 'customer', 'user'])->where('id', $id)->get();
        return response()->json(['success' => $order], $this->successStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, order $order)
    {
        $validator = Validator::make($request->all(), [
            'type'              => 'required',
            'customer_id'       => 'required',
            'user_id'           => 'required',
            'cargo_id'          => 'required',
            'no_item'           => 'required',
            'total_no_item'     => 'required',
            'order_status'      => 'required',
            'items'             => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        $input                    = $request->all();
        $customers = customer::where('name', $request->customer_id)->first();
        if ($customers) {
            $input['customer_id'] = $customers->id;
        }else{
            $customer = customer::create([
                'name'     =>  $request->customer_id
            ]);
            $input['customer_id'] = $customer->id;
        }
        $order->update($input);
        $order_ticket = $order->order_ticket;
        item::where('order_id', $order->id)->delete();

        $int = 0;
        $items = [];
        $itemss = json_decode($request->items);
        foreach($itemss as $key => $item){
            $data = [
                'order_id'          => $order->id,
                'name'              => $item->name,
                'quantity'          => $item->quantity,
                'actual_quantity'   => !empty($item->actual_quantity) ? $item->actual_quantity : '0',
                'detail'            => $item->detail,
                'status'            => $item->status,
            ];
            $items[]  =   item::create($data);
            $int++;
        }
        $activityInsert = [
            'order_id'              => $order->id,
            'user_id'               => $request->user_id,
            'status'                => $request->order_status,
            'is_back'               => !empty($request->is_back) ? $request->is_back : NULL ,
            'user_check'            => !empty($request->user_check) ? $request->user_check : NULL,
        ];
        $activity   = activity::create($activityInsert);
        $orders     = order::with(['items.product', 'cargo', 'activity.user', 'user'])->where('id', $order->id)->get();
        $success    = [
            'order' => $orders,
        ];

        $user_name   = User::find($request->user_id);
        if ($request->type == 1) {
            $message = $order_ticket ." Order Updated From ".$user_name->name;
            $notification = new Notification;
            $from       =  'HP Plus';
            $users = User::all();
            $notification->toMultiDevice($users, $from,$message,null,null);
        }

        return response()->json(['success'=>$success], $this->successStatus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = order::where('id', $id)->first();
        if ($order->type == 1) {
            $message = $order->order_ticket ." Order Deleted ";
            $notification = new Notification;
            $from       =  'HP Plus';
            $users = User::all();
            $notification->toMultiDevice($users, $from,$message,null,null);
        }
        order::with('items')->where('id', $id)->delete();
        return response()->json(['success' => $order], $this->successStatus);
    }

    public function CustomerSearch(){
        $name = customer::select('name')->get();
        return response()->json(['success' => $name], $this->successStatus);
    }

    public function orderstatus(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'user_id'           => 'required',
            'order_status'      => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        $input = $request->only('user_id', 'order_status','carteen_no');

        if (empty($request->carteen_no) || $request->carteen_no == "null" ) {
            unset($input['carteen_no']);
        }
        $order = order::find($id);
        $order->update($input);
        $activityInsert = [
            'order_id'              => $order->id,
            'user_id'               => $request->user_id,
            'status'                => $request->order_status,
            'is_back'               => !empty($request->is_back) ? $request->is_back : NULL,
            'user_check'            => !empty($request->user_check) ? $request->user_check : NULL,
        ];

        $activity   = activity::create($activityInsert);
        $success    = [
            'status' => $order->order_status
        ];

        $user_name   = User::find($request->user_id);
        if ($order->type == 1) {
            if($order->order_status == 1){
                $name_status = "Pending";
            }else if($order->order_status == 2){
                $name_status = "packed";
            }else if($order->order_status == 3){
                $name_status = "Delivered";
            }else if($order->order_status == 4){
                $name_status = "Billed";
            }else if($order->order_status == 5){
                $name_status = "Archived";
            }
            $message = $order->order_ticket ." Order Status ".$name_status." From ".$user_name->name;
            $notification = new Notification;
            $from       =  'HP Plus';
            $users = User::all();
            $notification->toMultiDevice($users, $from,$message,null,null);
        }

        return response()->json(['success' => $success], $this->successStatus);
    }

    public function OrderSearch(Request $request){

        $order_id   = $request->order_ticket;
        $date       = $request->date;
        $item       = $request->item_name;
        $name       = $request->customer_name;

        $name = order::with('customer', 'items', 'cargo', 'activity.user', 'user')
                        ->when(!empty($order_id) , function ($query) use($order_id){
                            return $query->where('order_ticket', 'LIKE', '%'.$order_id.'%');
                        })->when(!empty($date) , function ($query) use($date){
                            return $query->where('created_at', 'LIKE', '%'.$date.'%');
                        })->when(!empty($name) , function ($query) use($name){
                            return $query->whereHas('customer', function ($q) use ($name){
                                return $q->where('name', 'LIKE', '%'.$name.'%');
                            });
                        })->when(!empty($item) , function ($query) use($item){
                            return $query->whereHas('items', function ($q) use ($item){
                                return $q->where('name', 'LIKE', '%'.$item.'%');
                            });
                        })
                        ->latest()
                        ->get();

        return response()->json(['success' => $name], $this->successStatus);
    }
}
