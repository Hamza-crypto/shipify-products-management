@extends('layouts.app')

@section('title', 'Edit Order')
@section('title', 'Edit Order')
@php
    $role = Auth()->user()->role;

 $customer = false;
 $user = false;
 $admin = false;

  if( $role == 'customer' ){
       $customer = true;
  }
 else
     if( $role == 'user' )
     { $user = true; }
 else
     { $admin = true; }

 $admin_note = false;
 $user_note = false;

 if( isset($order->screenshot->assist_note) )
     {
         $admin_note = true;
     }

if( isset($order->screenshot->user_note) ){
      $user_note = true;
 }
@endphp
@section('content')

    <h1 class="h3 mb-3">Edit Order</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <x-alert type="success">{{ session('success') }}</x-alert>
                    @endif

                    <form method="post" action="{{ route('orders.update' ,$order->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')


                        <div class="form-group">
                            <label for="number">Card Number</label>
                            <input
                                class="form-control form-control-lg mb-3 @error('card_number') is-invalid @enderror "
                                type="number"
                                name="card_number"
                                placeholder="Enter card number"
                                @if ($role == 'customer')
                                disabled
                                @endif
                                value="{{ old('card_number', $order->card_number) }}"
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
                                        class="form-control form-control-lg mb-3 @error('month') is-invalid @enderror"
                                        type="text"
                                        name="month"
                                        placeholder="MM"
                                        min="01"
                                        max="12"
                                        @if ($role == 'customer')
                                        disabled
                                        @endif
                                        value="{{ old('month', $order->month) }}"
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
                                        class="form-control form-control-lg mb-3 @error('year') is-invalid @enderror"
                                        type="text"
                                        name="year"
                                        placeholder="YY"
                                        @if ($role == 'customer')
                                        disabled
                                        @endif
                                        value="{{ old('year',$order->year) }}"
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
                                        class="form-control form-control-lg mb-3 @error('cvc') is-invalid @enderror"
                                        type="text"
                                        name="cvc"
                                        placeholder="XXX"
                                        @if ($role == 'customer')
                                        disabled
                                        @endif
                                        value="{{ old('cvc', $order->cvc) }}"
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
                                class="form-control form-control-lg mb-3 @error('amount') is-invalid @enderror"
                                type="number"
                                name="amount"
                                step="0.01"
                                @if ($role == 'customer')
                                disabled
                                @endif
                                placeholder="Enter amount"
                                min="1"
                                max="500"
                                step="0.01"
                                value="{{ old('amount' ,$order->amount)}}"
                            />
                            @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Current Status</label>
                            <select name="status" @if ($user || $order->status!= 'pending' && !$admin) disabled
                                    @endif class="form-control form-control-lg mb-3 form-select flex-grow-1">
                                <option value="pending" @if ($order->status== 'pending') selected @endif>Pending
                                </option>
                                <option value="accepted" @if ($order->status== 'accepted') selected @endif>Accepted
                                </option>
                                <option value="declined" @if ($order->status== 'declined') selected @endif>Declined
                                </option>
                            </select>
                        </div>


                        @if( $admin )
                            @if($admin_note)
                                <hr class="my-4">
                                <div id="introduction" class="mb-5">
                                    <h3>Admin Note</h3>
                                    <p class="text-lg">
                                        {!! $order->screenshot->assist_note !!}
                                    </p>
                                </div>
                            @endif

                            @if($user_note)
                                <hr>
                                <div id="introduction" class="mb-5">
                                    <h3>Customer Note</h3>
                                    <p class="text-lg">
                                        {!! $order->screenshot->user_note !!}
                                    </p>
                                </div>
                            @endif
                        @endif  {{--Admin end--}}
                        @if( $customer )

                            <div
                                class="form-group">
                                <label for="description"> Admin Note </label>
                                <textarea
                                    class="form-control"
                                    name="assist_note"
                                    rows="2"
                                    placeholder="Please type here"
                                    id="text-area-description">

                            {{ isset($order->screenshot->assist_note) ? $order->screenshot->assist_note : ''}}
                        </textarea>
                            </div>


                            @if($user_note)
                                <hr>
                                <div id="introduction" class="mb-5">
                                    <h3>Customer Note</h3>
                                    <p class="text-lg">
                                        {!! $order->screenshot->user_note !!}
                                    </p>

                                </div>


                            @endif

                        @endif



                        @if( $user )

                            @if($admin_note)
                                <hr class="my-4">
                                <div id="introduction" class="mb-5">
                                    <h3>Admin Note</h3>
                                    <p class="text-lg">
                                        {!! $order->screenshot->assist_note !!}
                                    </p>

                                </div>

                            @endif

                            <hr>
                            <div
                                class="form-group">
                                <h3> Your Note</h3>

                                <textarea
                                    class="form-control"
                                    name="user_note"
                                    rows="2"
                                    id="text-area-description"
                                    placeholder="Please provide jusification for problem,specified by Admin"

                                >
                                    {{ isset($order->screenshot->user_note) ? $order->screenshot->user_note : ''}}
                        </textarea>
                            </div>

                        @endif

                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary">Update Order</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
    @if ( ! $user && sizeof($activities)>0 )

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Activity Changelog</h5>
                    </div>
                    <div class="card-body">
                        <ul class="timeline mt-2 mb-0">
                            @foreach( $activities as $activity)
                                <li class="timeline-item">
                                    <span
                                        class="float-end text-muted text-sm"> {{ $activity->created_at->diffForHumans() }}</span>
                                    <p> {{ $activity->title   }} </p>
                                </li>
                            @endforeach


                        </ul>

                    </div>
                </div>
            </div>
        </div>
    @endif


@endsection

@section('scripts')
    @include('pages.ckeditor')
@endsection
