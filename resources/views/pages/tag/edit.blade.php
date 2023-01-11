@extends('layouts.app')

@section('title', 'Edit Gateway')
@php
    $role = Auth()->user()->role;
@endphp
@section('content')

    <h1 class="h3 mb-3">Edit Gateway</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <x-alert type="success">{{ session('success') }}</x-alert>
                    @endif

                    <form method="post" action="{{ route('gateways.update' ,$gateway->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input
                                class="form-control form-control-lg mb-3 @error('title') is-invalid @enderror "
                                type="text"
                                name="title"
                                placeholder="Enter gateway title "
                                value="{{ old('title', $gateway->title) }}"
                            />
                            @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="title">API Key</label>
                            <input
                                class="form-control form-control-lg mb-3 @error('api_key') is-invalid @enderror "
                                type="text"
                                name="api_key"
                                placeholder="Enter API Key "
                                value="{{ old('api_key', $gateway->api_key) }}"
                            />
                            @error('api-key')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="title">API Secret</label>
                            <input
                                class="form-control form-control-lg mb-3 @error('api_secret') is-invalid @enderror "
                                type="text"
                                name="api_secret"
                                placeholder="Enter API Secret "
                                value="{{ old('api_secret', $gateway->api_secret) }}"
                            />
                            @error('api_secret')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary">Update Gateway</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

