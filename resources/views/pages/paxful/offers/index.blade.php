@extends('layouts.app')

@section('title', __('Offers'))

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#offers-table').DataTable();
        });
    </script>
@endsection

@section('content')
    <h1 class="h3 mb-3">{{ 'My Offers' }}</h1>

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @endif

    <div class="row">
        <div class="col-12">
            @if( isset($offers) && $offers == 0 )
               @include('pages.paxful.api_keys_manual')
            @else
                <div class="card">
                    <div class="card-body">

                        @if(session('delete'))
                            <x-alert type="danger">{{ session('delete') }}</x-alert>
                        @elseif(session('password_update'))
                            <x-alert type="success">{{ session('password_update') }}</x-alert>
                        @elseif(session('account'))
                            <x-alert type="success">{{ session('account') }}</x-alert>
                        @endif


                        <table id="offers-table" class="table table-striped" style="width:100%">
                            <thead>
                            <tr>
                                <th>{{ 'ID' }}</th>
                                <th>{{ 'Payment Method' }}</th>
                                <th>{{ 'Margin' }}</th>
                                <th>{{ 'Offer Type' }}</th>
                                <th>{{ 'Active' }}</th>
                                <th>{{ 'Currency' }}</th>
                                <th>{{ 'Min' }}</th>
                                <th>{{ 'Max' }}</th>
                                <th>{{ 'Action' }}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($offers as $offer)

                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td> {{ $offer->payment_method_name }}</td>
                                    <td> {{ $offer->margin }}</td>
                                    <td> {{ $offer->offer_type }}</td>
                                    @if ($offer->active)
                                        <td><i class="fa fa-check text-success"></i></td>
                                    @else
                                        <td><i class="fa fa-times text-danger"></i></td>
                                    @endif

                                    <td> {{ $offer->currency_code }}</td>
                                    <td> {{ $offer->fiat_amount_range_min }}</td>
                                    <td> {{ $offer->fiat_amount_range_max }}</td>

                                    <td class="table-action">
                                        <a href="{{ route('api.offer.edit', $offer->offer_hash ) }}" class="btn"
                                           style="display: inline">
                                            <i class="fa fa-edit text-info"></i>
                                        </a>
                                        <a href="{{ $offer->offer_link }}" class="btn" style="display: inline">
                                            <i class="fa fa-eye text-warning"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif


        </div>
    </div>
@endsection
