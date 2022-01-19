<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
