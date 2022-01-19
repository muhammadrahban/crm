@extends('layouts.front')

@section('title', 'dashboard')

@section('content')



<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Workers</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">List Workers</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                List Workers
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->is_admin == '1' ? "Admin" : "worker"}}</td>
                                <td>{{$user->status == '1' ? "Active" : "Inactive"}}</td>
                                <td>{{$user->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{route('worker.edit', $user->id )}}"><button type="button" class="btn btn-success"><i class="fas fa-edit"></i></button></a>
                                    <button type="button" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

@endsection
