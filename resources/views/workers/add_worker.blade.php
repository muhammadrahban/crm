@extends('layouts.front')

@section('title', 'dashboard')

@section('content')



<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Worker</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Add Worker</li>
        </ol>
        @if ($isEdit)
        <form method="POST" action="{{route('worker.update', $user->id)}} ">
            @csrf
            @method("PUT")
        @else
            <form method="POST" action="{{route('worker.store')}} ">
                @csrf
        @endif
            <div class="form-group mb-4">
                <label for="inputname">Username</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" {{ $isEdit ? "disabled" : "" }} id="inputname" placeholder="Enter Name" @if ($isEdit)
                    value="{{$user->name}}"
                @endif>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-4">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ $isEdit ? "disabled" : "" }} id="exampleInputPassword1" placeholder="Password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label> User Type</label>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_admin" id="Admin" value="1" @if ($isEdit)
                            {{ $user->is_admin == '1' ? 'checked' : '' }}
                        @endif>
                        <label class="form-check-label" for="Admin">
                          Is Admin
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="radio" name="is_admin" id="is_worker" value="2" @if ($isEdit)
                        {{ $user->is_admin == '2' ? 'checked' : '' }}
                    @endif>
                        <label class="form-check-label" for="is_worker">
                          Is Worker
                        </label>
                    </div>
                </div>
                @error('is_admin')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="status" value="1" id="status" @if ($isEdit)
                {{ $user->status == '1' ? 'checked' : '' }}
            @endif>
                <label class="form-check-label" for="status">
                    Status
                </label>
            </div>


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</main>

@endsection
