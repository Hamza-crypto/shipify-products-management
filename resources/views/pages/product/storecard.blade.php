@extends('layouts.app')

@section('title', 'Add Order')

@section('scripts')

@endsection
@section('content')

        <h1 class="h3 mb-3">Add New Order (Store Card) </h1>

        <div class="row" id="storecard">

            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        @if(session('success'))
                            <x-alert type="success">{{ session('success') }}</x-alert>
                        @endif
                        @if(session('error'))
                            <x-alert type="danger">{{ session('error') }}</x-alert>
                        @endif
                        @if(session('warning'))
                            <x-alert type="warning">{{ session('warning') }}</x-alert>
                        @endif

                        <form method="post" action="{{ route('order.store.store_card') }}">
                            @csrf

                            <input type="hidden" name="month" value="-">
                            <input type="hidden" name="year" value="-">
                            <input type="hidden" name="cvc" value="-">

                            <div class="form-group">
                                <label for="number">Card Number</label>
                                <input
                                    class="form-control form-control-lg @error('card_number') is-invalid @enderror"
                                    type="number"
                                    name="card_number"
                                    placeholder="Enter card number"
                                    value="{{ old('card_number' )}}"
                                />

                                @error('card_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="pin">PIN</label>
                                <input
                                    class="form-control form-control-lg @error('pin') is-invalid @enderror"
                                    type="number"
                                    name="pin"
                                    placeholder="Enter card PIN"
                                    value="{{ old('pin' )}}"
                                />

                                @error('pin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="year">Amount ($)</label>
                                <input
                                    class="form-control form-control-lg @error('amount') is-invalid @enderror"
                                    type="number"
                                    name="amount"
                                    step="0.01"
                                    placeholder="Enter amount"
                                    value="{{ old('amount' )}}"
                                />
                                @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>


                            @if(sizeof($tags) > 0)
                                <div class="form-group">
                                    <label for="role"> Tag </label>
                                    <select id="role"
                                            class="form-control form-control-lg select2 @error('tag') is-invalid @enderror"
                                            name="tag" data-toggle="select2">
                                        <option value="0">Select</option>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                                        @endforeach

                                    </select>
                                    @error('tag')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                        @endif


                            <div class="form-group">
                                <button type="submit" id="add" class="btn btn-lg btn-primary">Add New
                                    Card
                                </button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>

@endsection
