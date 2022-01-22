@extends('layouts.front')

@section('title', 'dashboard')

@section('content')



<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Category</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Add Category</li>
        </ol>
        @if ($isEdit)
        <form method="POST" action="{{route('category.update', $category->id)}} ">
            @csrf
            @method("PUT")
        @else
            <form method="POST" action="{{route('category.store')}} ">
                @csrf
        @endif
            <div class="form-group mb-4">
                <label for="inputname">Category Name</label>
                <input type="text" name="name" class="form-control @error('naem') is-invalid @enderror" id="inputname"  placeholder="name" @if ($isEdit)
                    value="{{ $category->name }}"
                @endif>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-4">
                <label for="InputCategories">Category List</label>
                <select name="child" id="InputCategories" class="form-control @error('child') is-invalid @enderror">
                    <option value="0">Parent Category</option>
                    @foreach ($categories as $cats)
                        <option value="{{$cats->id}}" @if ($isEdit) {{$category->child == $cats->id ? "selected" : ""}} @endif>{{$cats->name}}</option>
                    @endforeach
                </select>
                @error('child')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</main>

@endsection
