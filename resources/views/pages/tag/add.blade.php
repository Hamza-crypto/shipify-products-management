@extends('layouts.app')

@section('title', 'Add Tag')

@section('content')

    <h1 class="h3 mb-3">Add New Tag</h1>

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


                    <form method="post" action="{{ route('tags.store') }}">
                        @csrf

                        <x-input type="text" label="Title" placeholder="Enter tag title"></x-input>

                        <div class="form-group">

                            <button type="submit" class="btn btn-lg btn-primary">Add New Tag</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
