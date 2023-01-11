@extends('layouts.app')

@section('title', 'Edit Offer')

@section('scripts')


@endsection
@section('content')

    <h1 class="h3 mb-3">Edit Offer</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <x-alert type="success">{{ session('success') }}</x-alert>
                    @endif
                    @if(session('error'))
                        <x-alert type="error">{{ session('error') }}</x-alert>
                    @endif

                    <form method="post" action="{{ route('api.offer.update' ,$offer->id) }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-4">
                                {{--                                <div class="form-group">--}}
                                {{--                                    <label for="number">Margin</label>--}}
                                {{--                                    <input--}}
                                {{--                                        class="form-control form-control-lg mb-3  @if(session('margin_error')) is-invalid @endif"--}}
                                {{--                                        type="number"--}}
                                {{--                                        name="margin"--}}
                                {{--                                        placeholder="Margin"--}}
                                {{--                                        value="{{ old('margin', $offer->margin ) }}"--}}
                                {{--                                    />--}}
                                {{--                                    @if(session('margin_error'))--}}
                                {{--                                        <div class="invalid-feedback">--}}
                                {{--                                            {{  session('margin_error') }}--}}
                                {{--                                        </div>--}}

                                {{--                                    @endif--}}

                                {{--                                </div>--}}
                                <x-input_offer
                                    type="number"
                                    name="margin"
                                    label="Margin"
                                    placeholder="Margin"
                                    value="{{ $offer->margin  }}"
                                ></x-input_offer>

                            </div>

                            <div class="col-4">

                                <x-input_offer
                                    type="number"
                                    name="fiat_min"
                                    label="Fiat amount (min)"
                                    placeholder="Fiat amount range (min)"
                                    value="{{ $offer->fiat_amount_range_min  }}"
                                ></x-input_offer>

                            </div>
                            <div class="col-4">
                                <x-input_offer
                                    type="number"
                                    name="fiat_max"
                                    label="Fiat amount (max)"
                                    placeholder="Fiat amount range (max)"
                                    value="{{ $offer->fiat_amount_range_max  }}"
                                ></x-input_offer>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label> Offer Terms </label>
                                 <textarea
                                     class="form-control"
                                     name="offer_terms"
                                     rows="3"
                                     placeholder="Please provide jusification for problem specified by Admin">{{ $offer->offer_terms }}
                             </textarea>
                                </div>
                            </div>


                        </div >
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-6">
                                <button type="submit" class="btn btn-lg btn-primary">Update Offer</button>
                            </div>


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

                    <form method="post" action="{{ route('api.offer.update' ,$offer->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-6">
                                <h1>Current Status:
                                    @if($offer->active)
                                        <button class="btn btn-success"><i class="fas fa-check"></i> Active</button>
                                    @else
                                        <button class="btn btn-danger"><i class="fas fa-times"></i> Inactive</button>
                                    @endif


                                </h1>

                            </div>
                            <div class="col-6">
                                @if($offer->active)
                                    <a class="btn btn-danger" href="{{ route('api.offer.deactivate', $offer->id) }}" ><i class="fas fa-times"></i>  Mark as Inactive</a>
                                @else
                                    <a class="btn btn-success" href="{{ route('api.offer.activate', $offer->id) }}" ><i class="fas fa-check"></i> Mark as Active</a>

                                @endif

                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

