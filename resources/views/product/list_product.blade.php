@extends('layouts.front')

@section('title', 'dashboard')

@section('content')



<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Items</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">List Items</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                List Items
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($product as $pro)
                            <tr>
                                <td>{{$pro->id}}</td>
                                <td>{{$pro->name}}</td>
                                <td>{{$pro->category->name}}</td>
                                <td>{{$pro->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{route('product.edit', $pro->id )}}"><button type="button" class="btn btn-success"><i class="fas fa-edit"></i></button></a>
                                    <a href="{{ route('product.destroy', $pro->id) }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('product_delete{{$pro->id}}').submit();">
                                        <button type="button" class="btn btn-danger" ><i class="far fa-trash-alt"></i></button>
                                    </a>
                                    <form id="product_delete{{$pro->id}}"  action="{{ route('product.destroy', $pro->id) }}" method="POST" class="d-none">
                                        @method('delete')
                                        @csrf
                                    </form>

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
