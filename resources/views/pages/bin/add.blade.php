@extends('layouts.app')

@section('title', 'Add BIN')

@section('content')

    <h1 class="h3 mb-3">Add New BIN</h1>

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


                    <form method="post" action="{{ route('bins.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="number">BIN</label>
                            <input
                                class="form-control form-control-lg @error('number') is-invalid @enderror"
                                type="number"
                                name="number"
                                placeholder="Enter 6 digit BIN"
                                value="{{ old('number' )}}"
                            />

                            @error('number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="number">Minimum Amount</label>
                                    <input
                                        class="form-control form-control-lg @error('min_amount') is-invalid @enderror"
                                        type="number"
                                        name="min_amount"
                                        placeholder="Enter minimum amount"
                                        value="{{ old('min_amount' )}}"
                                    />

                                    @error('min_amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="number">Maximum Amount</label>
                                    <input
                                        class="form-control form-control-lg @error('max_amount') is-invalid @enderror"
                                        type="number"
                                        name="max_amount"
                                        placeholder="Enter maximum amount"
                                        value="{{ old('max_amount')}}"
                                    />

                                    @error('max_amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="gateway">Select Gateway</label>
                            <select name="gateway_id" id="gateway"
                                    class="form-control form-select custom-select select2"
                                    data-toggle="select2">
                                @foreach($gateways as $gateway)
                                    <option
                                        value="{{ $gateway->id }}">{{ $gateway->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">

                            <button type="submit" class="btn btn-lg btn-primary">Add New BIN</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
