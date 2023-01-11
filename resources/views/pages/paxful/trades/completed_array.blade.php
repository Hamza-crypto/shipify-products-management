@extends('layouts.app')

@section('title', __('Trades'))

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#trades-table').DataTable();
        });
    </script>
@endsection

@section('content')
    <h1 class="h3 mb-3">{{ 'All Trades' }}</h1>

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                    <div class="card-body">

                        @if(session('delete'))
                            <x-alert type="danger">{{ session('delete') }}</x-alert>
                        @elseif(session('password_update'))
                            <x-alert type="success">{{ session('password_update') }}</x-alert>
                        @elseif(session('account'))
                            <x-alert type="success">{{ session('account') }}</x-alert>
                        @endif


                        <table id="trades-table" class="table table-striped" style="width:100%">
                            <thead>
                            <tr>
                                <th>{{ 'Hash' }}</th>
                                <th>{{ 'Buyer' }}</th>
                                <th>{{ 'Amount' }}</th>
                                <th>{{ 'Payment method' }}</th>
                                <th>{{ 'Offer Type' }}</th>
                                <th>{{ 'Status' }}</th>
                                <th>{{ 'Action' }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($trades as $trade)
                                <tr>
{{--                                    <td>{{ $loop['iteration }}</td>--}}
                                    <td> {{ $trade['trade_hash'] }}</td>
                                    <td> {{ $trade['buyer'] }}</td>
                                    <td> {{ $trade['fiat_amount_requested'] . ' ' . $trade['fiat_currency_code'] }}</td>
                                    <td> {{ $trade['payment_method_name'] }}</td>
                                    <td> {{ $trade['offer_type'] }}</td>
                                    <td> {{ $trade['status'] }}</td>

                                    <td class="table-action">
                                        <a href="{{ route('api.trade_chat', $trade['trade_hash'] ) }}" class="btn" style="display: inline">
                                            <i class="fa fa-envelope text-info"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
 @endsection
