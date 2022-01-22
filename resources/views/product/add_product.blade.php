@extends('layouts.front')

@section('title', 'dashboard')

@section('content')



<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Item</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Add Item</li>
        </ol>
        @if ($isEdit)
        <form method="POST" action="{{route('product.update', $product->id)}} ">
            @csrf
            @method("PUT")
        @else
            <form method="POST" action="{{route('product.store')}} ">
                @csrf
        @endif
            <div class="form-group mb-4">
                <label for="inputname">Item Name</label>
                <input type="text" name="name" class="form-control @error('naem') is-invalid @enderror" id="inputname"  placeholder="name" @if ($isEdit)
                    value="{{ $product->name }}"
                @endif>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-4">
                <label for="InputCategories">Category List</label>
                <select name="cat_id" id="InputCategories" class="form-control @error('child') is-invalid @enderror">
                    @foreach ($categories as $cats)
                        <option value="{{$cats->id}}" @if ($isEdit) {{$product->cat_id == $cats->id ? "selected" : ""}} @endif > {{$cats->child != 0 ? html_entity_decode('&#160;&#160;&#160;&#160;') : ''}} {{$cats->name}}</option>
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
