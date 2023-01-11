@extends('layouts.app')

@section('title', 'Add Gateway')

@section('content')

    <h1 class="h3 mb-3">Add New Gateway</h1>

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


                    <form method="post" action="{{ route('gateways.store') }}">
                        @csrf

                        <x-input type="text" label="Title" placeholder="Enter gateway title"></x-input>
                        <x-input type="text" label="API Key" name="api_key" placeholder="Enter API Key"></x-input>
                        <x-input type="text" label="API Secret" name="api_secret" placeholder="Enter API Secret"></x-input>

                        <div class="form-group">

                            <button type="submit" class="btn btn-lg btn-primary">Add New Gateway</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
