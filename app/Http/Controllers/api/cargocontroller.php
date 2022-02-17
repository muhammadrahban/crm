<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\cargo;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;
class cargocontroller extends Controller
{
    public $successStatus = 200;
    public $errorStatus = 401;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cargo = cargo::all();
        return response()->json(['success' => $cargo], $this->successStatus);
    }

    public function search(Request $request){
        $first      = $request->first_date;
        $second     = $request->second_date;
        $cargo = cargo::withCount('order')
            ->when(!empty($first) && !empty($second), function($query) use ($first, $second){
                return $query->whereHas('order', function($q) use ($first, $second) {
                    // dd($first ." - ".$second);
                    return $q->whereBetween(DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d'))"), [$first, $second]);
                });
            })
            ->when(!empty($first) && empty($second), function($query) use ($first){
                return $query->whereHas('order', function($q) use ($first){
                    return $q->where('created_at', 'LIKE', '%'.$first.'%');
                });
            })
            ->when(!empty($second) && empty($first), function($query) use ($second){
                return $query->whereHas('order', function($q) use ($second){
                    return $q->where('created_at', 'LIKE', '%'.$second.'%');
                });
            })
            ->latest()
            ->get();
        return response()->json(['success' => $cargo], $this->successStatus);
    }

    public function cargoDetail(Request $request){
        $first      = $request->first_date;
        $second     = $request->second_date;
        $cargo_id   = $request->cargo_id;

        $cargo = order::with(['items.product', 'cargo', 'activity.user', 'customer', 'user'])
        ->when(!empty($first) && !empty($second), function($q) use ($first, $second){
            return $q->whereBetween(DB::raw("(DATE_FORMAT(created_at, '%Y-%m-%d'))"), [$first, $second]);
        })
        ->when(!empty($first) && empty($second), function($query) use ($first){
            return $query->where('created_at', 'LIKE', '%'.$first.'%');
        })
        ->when(!empty($second) && empty($first), function($query) use ($second){
            return $query->where('created_at', 'LIKE', '%'.$second.'%');
        })
        ->whereHas('cargo', function($query) use ($cargo_id){
            return $query->where('id', $cargo_id);
        })->latest()->get();
        return response()->json(['success' => $cargo], $this->successStatus);
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
        $validator = Validator::make($request->all(),[
            'name'  => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], $this->errorStatus);
        }
        $input  = $request->all();
        $cargo  = Cargo::create($input);

        return response()->json(['success' => $cargo], $this->successStatus);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cargo = Cargo::find($id);
        return response()->json(['success' => $cargo], $this->successStatus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cargo = Cargo::find($id);
        return response()->json(['success' => $cargo], $this->successStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cargo $cargo)
    {
        $validator = Validator::make($request->all(),[
            'name'  => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], $this->errorStatus);
        }
        $input  = $request->all();
        $cargo->update($input);
        return response()->json(['success' => $cargo], $this->successStatus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cargo = Cargo::find($id)->delete();
        return response()->json(['success' => $cargo], $this->successStatus);
    }
}
