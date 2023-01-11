@extends('layouts.app')

@section('title', 'Categories')

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#category-table').DataTable();
        });
    </script>
@endsection

@section('content')
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    <h1 class="h3 mb-3">All Categories</h1>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table id="category-table" class="table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <active-trades/>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>


                                <td>{{ $category->type }}</td>

                                <td>{{ $category->created_at->diffForHumans() }}</td>
                                <td class="table-action">

                                    <form method="post" action="{{ route('order_categories.destroy', $category->id) }}"
                                          style="display: inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn text-danger"
                                                onclick="confirm('Are you sure you want to delete this Category?')"
                                                href="{{ route('order_categories.destroy', $category->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
