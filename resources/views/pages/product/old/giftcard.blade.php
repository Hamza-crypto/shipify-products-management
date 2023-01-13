@extends('layouts.app')

@section('title', 'Add Order')

@section('scripts')
    <script>
        $('#add').click(function(){
            // alert('sss');
             $('#loader').toggleClass('d-none');
        });
    </script>
@endsection
@section('content')

        <h1 class="h3 mb-3">Add New Order (Gift card) </h1>

        <div class="row" id="giftcard">

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

                        <form method="post" action="{{ route('orders.store') }}">
                            @csrf


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


                            <div class="row" style="">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="month">Month</label>
                                        <input
                                            class="form-control form-control-lg @error('month') is-invalid @enderror"
                                            type="text"
                                            name="month"
                                            placeholder="MM"
                                            min="01"
                                            max="12"
                                            minlength="2"
                                            value="{{ old('month') }}"
                                        />
                                        @error('month')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="year">Year</label>
                                        <input
                                            class="form-control form-control-lg @error('year') is-invalid @enderror"
                                            type="text"
                                            name="year"
                                            min="21"
                                            max="99"
                                            minlength="2"
                                            placeholder="YY"
                                            value="{{ old('year') }}"
                                        />
                                        @error('year')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="cvc">CVC</label>
                                        <input
                                            class="form-control form-control-lg @error('cvc') is-invalid @enderror"
                                            type="text"
                                            name="cvc"
                                            placeholder="XXX"
                                            value="{{ old('cvc') }}"
                                        />
                                        @error('cvc')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>


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
