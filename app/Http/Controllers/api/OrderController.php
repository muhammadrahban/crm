<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\item;
use App\Models\order;
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
        $order = order::with(['items', 'cargo'])->get();
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
            'user_id'           => 'required',
            'no_item'           => 'required',
            'total_no_item'     => 'required',
            'customer_name'     => 'required',
            'carteen_no'        => 'required',
            'cargo_id'          => 'required',
            'remarks'           => 'required',
            'status'            => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        $input    =  $request->all();
        $orders   =  order::create($input);
        $int = 0;
        $items = [];
        foreach($request->name as $item){
            $data = [
                'order_id'     => $orders->id,
                'name'         => $item,
                'quantity'     => $input['quantity'][$int],
                'status'       => $input['status'][$int],
            ];
            $items[]  =   item::create($data);
            $int++;
        }

        $success = [
            'order' => $orders,
            'items' => $items,
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
        $order = order::with(['items', 'cargo'])->where('id', $id)->get();
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
        $order = order::with(['items', 'cargo'])->where('id', $id)->get();
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
            'user_id'           => 'required',
            'no_item'           => 'required',
            'total_no_item'     => 'required',
            'remarks'           => 'required',
            'status'            => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);
        }
        $input                  =  $request->all();
        $order->update($input);
        item::where('id', $order->id)->delete();

        $int = 0;
        $items = [];
        foreach($request->name as $item){
            $data = [
                'order_id'     => $order->id,
                'name'         => $item,
                'quantity'     => $input['quantity'][$int],
                'status'       => $input['status'][$int],
            ];
            $items[]  =   item::create($data);
            $int++;
        }

        $success = [
            'order' => $order,
            'items' => $items,
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
}
