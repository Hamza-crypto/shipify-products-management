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

{{--    @if(!$open)--}}
{{--        <div class="row">--}}

{{--            <div class="col-12">--}}

{{--                <div class="card border border-primary">--}}
{{--                    <!-- .card-body -->--}}
{{--                    <div class="card-body" style=" margin: auto;--}}
{{--  width: 50%;--}}
{{--  padding: 30px;--}}
{{--text-align: center;">--}}
{{--                        <h1 style="font-size: 5rem;">--}}
{{--                            <i class="fas fa-store-alt-slash"></i></h1>--}}
{{--                        <h3 class="state-header"--}}
{{--                            style="font-size: 2.5rem; font-weight: bold; margin-top:20px;"> {{ $msg_title }} </h3>--}}
{{--                        <p class="state-description" style="font-size: 0.8rem;"> {{ $msg }} </p>--}}

{{--                    </div><!-- /.card-body -->--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
{{--    @if($open)--}}


        <h1 class="h3 mb-3">Add New Order </h1>

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

{{--                            @if(sizeof($categories) > 0)--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="role"> Card Category </label>--}}
{{--                                    <select id="role"--}}
{{--                                            class="form-control form-control-lg select2 @error('role') is-invalid @enderror"--}}
{{--                                            name="category" data-toggle="select2">--}}
{{--                                        @foreach($categories as $category)--}}
{{--                                            <option value="{{ $category->type }}"--}}
{{--                                                    @if( in_array($category->type, $non_available_categories_array ) ) disabled @endif>{{ $category->type }}</option>--}}
{{--                                        @endforeach--}}

{{--                                    </select>--}}
{{--                                    @error('role')--}}
{{--                                    <div class="invalid-feedback">--}}
{{--                                        {{ $message }}--}}
{{--                                    </div>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            @endif--}}


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


        <div id="loader" class="row text-center d-none">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Checking your card</h5>
                    <h6 class="card-subtitle text-muted">Please wait while we check your card.</h6>
                </div>
                <div class="card-body">

                    <div class="mb-2">

                        <div class="spinner-border text-primary me-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        </div>

{{--        @if(old('image'))--}}

{{--            <div class="row">--}}
{{--                <div class="col-12">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-header text-center">--}}
{{--                            <h5 class="card-title mb-0">Balance Screenshot</h5>--}}
{{--                        </div>--}}
{{--                        <div class="card-body">--}}
{{--                            <img class="card-img-top" src="{{old('image')}}">--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}




{{--    @endif--}}



@endsection
