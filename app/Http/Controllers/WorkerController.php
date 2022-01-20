<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('workers.list_worker', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit = false;
        return view('workers.add_worker', compact('isEdit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                      =>  'required|max:255|unique:users,name',
            'password'                  =>  'required',
            'is_admin'                  =>  'required',
            'status'                    =>  'required',
            'device_token'              =>  'nullable',
        ]);

        $input                      =   $request->all();
        if($request->is_admin == '1'){
            $input['is_admin']          =   "1";
        }else{
            $input['is_admin']          =   "2";
        }
        $input['password']          =   Hash::make($input['password']);
        $user                       =   User::create($input);

        return redirect()->route('worker.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $isEdit = true;
        $user = User::find($id);
        return view('workers.add_worker', compact('isEdit', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'is_admin'      =>  'required',
            'status'        =>  'required',
            'device_token'  =>  'nullable',
        ]);

        $input              =  $request->all();
        if($request->is_admin == '1'){
            $input['is_admin']  = "1";
        }else{
            $input['is_admin']  = "2";
        }
        $user       = User::find($id);
        // dd($user);
        $result     = $user->update($input);
        return redirect()->route('worker.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd($id);
    }
}
