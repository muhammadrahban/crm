@extends('layouts.front')

@section('title', 'dashboard')

@section('content')



<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Categories</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">List Categories</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                List Categories
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($categories as $cat)
                            <tr>
                                <td>{{$cat->id}}</td>
                                <td>{{$cat->name}}</td>
                                <td>{{$cat->child == 0 ? 'Main Category' : 'Sub Category'}}</td>
                                <td>{{$cat->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{route('category.edit', $cat->id )}}"><button type="button" class="btn btn-success"><i class="fas fa-edit"></i></button></a>
                                    <a href="{{ route('category.destroy', $cat->id) }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('product_delete{{$cat->id}}').submit();">
                                        <button type="button" class="btn btn-danger" ><i class="far fa-trash-alt"></i></button>
                                    </a>
                                    <form id="product_delete{{$cat->id}}"  action="{{ route('category.destroy', $cat->id) }}" method="POST" class="d-none">
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
