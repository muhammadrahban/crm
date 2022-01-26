<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\item;
use App\Models\activity;
use App\Models\order;
use App\Models\customer;
use Illuminate\Http\Request;
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
        $order = order::with(['items.product', 'cargo', 'activity.user', 'customer'])->get();
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
        $validator = Validator::make($request->all(), [
            'type'              => 'required',
            'no_item'           => 'required',
            'total_no_item'     => 'required',
            'customer_name'     => 'required',
            'carteen_no'        => 'required',
            'cargo_id'          => 'required',
            'remarks'           => 'required',
            'order_status'      => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        $input                 = $request->all();
        $customers = customer::where('name', $request->customer_name)->first();
        if ($customers) {
            $input['customer_name'] = $customers->id;
        }else{
            $customer = customer::create([
                'name'     =>  $request->customer_name
            ]);
            $input['customer_name'] = $customer->id;
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
        foreach($request->name as $item){
            $data = [
                'order_id'          => $orders->id,
                'name'              => $item,
                'quantity'          => $input['quantity'][$int],
                'detail'            => $input['detail'][$int],
                'actual_quantity'   => $input['actual_quantity'][$int],
                'status'            => $input['status'][$int],
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
        // dd($activity);
        $order = order::with(['items.product', 'cargo', 'activity.user'])->where('id', $orders->id)->get();
        $success = [
            'order' => $order,
        ];

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
        $order = order::with(['items.product', 'cargo', 'activity.user'])->where('id', $id)->get();
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
        $order = order::with(['items.product', 'cargo', 'activity.user'])->where('id', $id)->get();
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
            'customer_name'     => 'required',
            'carteen_no'        => 'required',
            'cargo_id'          => 'required',
            'no_item'           => 'required',
            'total_no_item'     => 'required',
            'remarks'           => 'required',
            'status'            => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        $customers = customer::where('name', $request->customer_name);get();
        $input                  =  $request->all();
        if ($customers) {
            $input['customer_name'] = $customers->id;
        }else{
            $customer = customer::create([
                'name'     =>  $request->customer_name
            ]);
            $input['customer_name'] = $customer->id;
        }
        $order->update($input);
        item::where('id', $order->id)->delete();

        $int = 0;
        $items = [];
        foreach($request->name as $item){
            $data = [
                'order_id'          => $orders->id,
                'name'              => $item,
                'quantity'          => $input['quantity'][$int],
                'detail'            => $input['detail'][$int],
                'actual_quantity'   => $input['actual_quantity'][$int],
                'status'            => $input['status'][$int],
            ];
            $items[] = item::create($data);
            $int++;
        }
        $activityInsert = [
            'order_id'              => $orders->id,
            'user_id'               => $request->user_id,
            'status'                => $request->status,
            'is_back'               => !empty($request->is_back) ? $request->is_back : NULL ,
            'user_check'            => !empty($request->user_check) ? $request->user_check : NULL,
        ];
        $activity   = activity::create($activityInsert);
        $orders     = order::with(['items.product', 'cargo', 'activity.user'])->where('id', $order->id)->get();
        $success    = [
            'order' => $orders,
        ];

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
        $order = order::with('items')->where('id', $id)->delete();
        return response()->json(['success' => $order], $this->successStatus);
    }

    public function CustomerSearch(){
        $name = customer::select('name')->get();
        return response()->json(['success' => $name], $this->successStatus);
    }
}
