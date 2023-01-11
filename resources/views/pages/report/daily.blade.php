@extends('layouts.app')

@section('title', 'Orders')
@php
    $role = Auth()->user()->role;
    $query = str_replace(url()->current(), '',url()->full());
@endphp

@section('scripts')
    <script>


        $(document).ready(function () {

            var urlParams = new URLSearchParams(window.location.search);

            var date_filter_value = 0;
            var gateway_filter_value = 999;
            var user_filter_value = 0;


            const regex = /date=(.*)&gateway=(\d*)&user=(\d*)/gm;
            const str = urlParams.toString();
            let m;

            while ((m = regex.exec(str)) !== null) {
                // This is necessary to avoid infinite loops with zero-width matches
                if (m.index === regex.lastIndex) {
                    regex.lastIndex++;
                }

                date_filter_value = m[1];
                gateway_filter_value = m[2];
                user_filter_value = m[3];
            }

            console.log(date_filter_value, gateway_filter_value, user_filter_value);

            $('#date').val(date_filter_value).trigger('change');
            $('#gateway').val(gateway_filter_value).trigger('change');

            $('#transactions-table').DataTable();


            $("input[id=\"daterange\"]").daterangepicker({
                autoUpdateInput: false,
            }).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            }).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

        });

        function get_query_params() {

            var date = $('#date').val();
            var gateway = $('#gateway').val();
            var user = $('#user').val();
            var manager = $('#manager').val();
            var sub_user = $('#sub_users').val();
            var daterange = $('#daterange').val();
            var queryString = 'date=' + date + '&gateway=' + gateway + '&user=' + user + '&manager=' + manager + '&sub_user=' + sub_user + '&daterange=' + daterange;
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + queryString;
            window.history.pushState({path: newurl}, '', newurl);

            var urlParams = new URLSearchParams(window.location.search);
            var query = urlParams.toString();
            let url = "{{ route('report.daily', ':id') }}";
            url = url.replace(':id', query);
            document.location.href = url;
        }

        $("#date").change(function () {

            var date_id = $('#date').val();
            console.log(date_id);
            if (date_id == 1000) {
                $('#date_range_div2').removeClass('d-none');
            } else {

                $('#date_range_div2').addClass('d-none');
                $('#daterange').val('');
            }


        });


        $("#manager").change(function () {

            var manager_id = $('#manager').val();


            let url = "{{ route('manager.users', [':id', 'action' => ':op'] )  }}";
            url = url.replace(':id', manager_id);
            $.ajax({
                url: url,
                success: function (response) {
                    console.log("Success: " + response);

                    $('#sub_users').html(response);


                },
                error: function (err) {
                    console.log("Error sending data to server: " + err);
                }
            });
        });

        $('.clear-dt-filters').on('click', function () {
            $('#date').val('1001').trigger('change');
            $('#gateway').val("999").trigger('change');
            $('#user').val('0').trigger('change');
            $('#manager').val('0').trigger('change');
            $('#sub_users').val('0').trigger('change');
            $('#daterange').val('');

            var queryString = 'search=' + data.search.value + '&status=' + data.status + '&paid_status=' + data.paid_status + '&daterange=' + data.daterange + '&user=' + data.user + '&used_status=' + data.used_status;
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + queryString;
            window.history.pushState({path: newurl}, '', newurl);
            location.reload();
        });

    </script>
@endsection
@section('content')
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>

    @endif


    <h1 class="h3 mb-3">Daily Report </h1>
    @include('pages.report._inc.stats')
    @include('pages.report._inc.filters')
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <div class="dt-buttons btn-group flex-wrap">

                        {{--Export button--}}

                    </div>

                    @if(session('delete'))
                        <x-alert type="danger">{{ session('delete') }}</x-alert>
                    @elseif(session('password_update'))
                        <x-alert type="success">{{ session('password_update') }}</x-alert>
                    @elseif(session('account'))
                        <x-alert type="success">{{ session('account') }}</x-alert>
                    @endif


                    <table id="transactions-table" class="table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th> ID</th>
                            <th> User</th>
                            <th> Card #</th>
                            <th> Amount</th>
                            <th> Status</th>
                            <th> Gateway</th>
                            <th>{{ 'Created at' }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>

                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->card_number }}</td>
                                <td>{{ $order->amount }}</td>
                                <td>
                                    @if ($order->status == 'accepted')
                                        <span class="badge badge-success">{{$order->status}}</span>
                                    @elseif ($order->status == 'pending')
                                        <span class="badge badge-warning">{{$order->status}}</span>
                                    @elseif ($order->status == 'declined')
                                        <span class="badge badge-danger">{{$order->status}}</span>
                                    @elseif ($order->status == 'canceled')
                                        <span class="badge badge-secondary">{{$order->status}}</span>
                                    @endif
                                </td>

                                @if(isset($order->gateway))
                                    <td>{{ $order->gateway->title  }}</td>
                                @else
                                    <td></td>
                                @endif
                                <td> {{ $order->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection


