@extends('layouts.app')

@section('title', 'Add New File ')

@section('scripts')
    <script>
        $('button').click(function () {
            return confirm('Are you sure?');
        });
    </script>
@endsection
@section('content')

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="danger">{{ session('error') }}</x-alert>
    @endif
    @if(session('warning'))
        <x-alert type="warning">{{ session('warning') }}</x-alert>
    @endif


    <h1 class="h3 mb-3">Add New File </h1>

    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('product.sku_update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label" for="store">{{ 'Store' }}</label>
                                    <select name="store" id="store"
                                            class="form-control form-select custom-select select2"
                                            data-toggle="select2">
                                        @foreach($stores as $store)
                                            <option
                                                value="{{ $store->id }}" {{ $store->id == request()->store ? 'selected' : '' }}>{{ $store->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label" for="status"> Type </label>
                                    <select name="key_type" id="status"
                                            class="form-control form-select custom-select select2"
                                            data-toggle="select2">
                                        <option value="sku" selected> SKU</option>
                                        <option value="upc"> UPC</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="mb-3">
                                <label class="form-label w-100"></label>
                                <input type="file" name="file">

                            </div>
                        </div>


                        <div class="form-group">
                            <button type="submit" id="add" class="btn btn-lg btn-primary">Upload File
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('product.delete') }}">
                        @csrf
                        <div class="row">
                            <div class="col-4">
                                <button type="submit" name="sku" class="btn btn-danger btn-lg"> Delete Products Without SKU</button>
                            </div>
                            <div class="col-4">
                                <button type="submit" name="upc" class="btn btn-danger btn-lg">  Delete Products Without UPC</button>
                            </div>

                            <div class="col-4">
                                <button type="submit" name="images" class="btn btn-danger btn-lg">  Delete Products Without Images</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

