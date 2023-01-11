@extends('layouts.app')

@section('title', 'Orders')
@php
    $role = Auth()->user()->role;
    $query = str_replace(url()->current(), '',url()->full());
@endphp
@section('scripts')
    <script>

        $(document).ready(function () {
            var user = $('#user').val();
            if (user == -100) {
                $('#user').val('2').trigger('change');
            }
            $('#orders-table').DataTable();
        });

        function get_query_params() {

            var user = $('#user').val();
            var queryString = 'user=' + user;
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + queryString;
            window.history.pushState({path: newurl}, '', newurl);

            var urlParams = new URLSearchParams(window.location.search);
            var query = urlParams.toString();
            let url = "{{ route('report.payable', ':id') }}";
            url = url.replace(':id', query);
            document.location.href = url;
        }


    </script>
@endsection

@section('content')
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>

    @endif


    <h1 class="h3 mb-3">Payable Amount Report</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form>
                        <input type="hidden" class="d-none" name="filter" value="true" hidden>
                        <div class="row">


                            <div class="col-3">
                                <div class="form-group">
                                    <label class="form-label" for="status">{{ __('User') }}</label>
                                    <select name="users" id="user"
                                            class="form-control form-select custom-select select2"
                                            data-toggle="select2">
                                        <option value="-100">{{ __('Select User') }}</option>
                                        @foreach($users as $user)
                                            <option
                                                value="{{ $user->id }}" {{ request()->user == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-4 mt-4">
                                <button type="button"
                                        class="btn btn-sm btn-primary apply-dt-filters mt-2"
                                        onclick="get_query_params()">{{ __('Apply') }}</button>
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

                    <table id="orders-table" class="table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Total Amount($)</th>
                            <th>Payable($)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td> {{ $order['date'] }} </td>
                                <td> {{ $order['total_amount'] }} </td>
                                <td> {{ $order['payable_amount'] }} </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


