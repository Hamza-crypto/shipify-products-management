@extends('layouts.app')

@section('title', 'Add Category')

@section('content')

    <h1 class="h3 mb-3">Add New Category</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <x-alert type="success">{{ session('success') }}</x-alert>
                    @endif

                    @if(session('error'))
                        <x-alert type="danger">{{ session('error') }}</x-alert>
                    @endif


                    <form method="post" action="{{ route('order_categories.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="number">Category</label>
                            <input
                                class="form-control form-control-lg @error('order_type') is-invalid @enderror"
                                type="text"
                                name="order_type"
                                placeholder="Enter Order Category"
                                value="{{ old('order_type' )}}"
                            />

                            @error('order_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">

                            <button type="submit" class="btn btn-lg btn-primary">Add New Category</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
