@extends('layouts.app')

@section('title', 'Orders')
@php
    $role = Auth()->user()->role;
    $query = str_replace(url()->current(), '',url()->full());
@endphp
@section('scripts')
    <script>
        function get_query_params() {
            var urlParams = new URLSearchParams(window.location.search);
            var query = urlParams.toString();
            let url = "{{ route('orders.export', ':id') }}";
            url = url.replace(':id', query);
            document.location.href = url;
        }

        function get_query_params2() {
            var urlParams = new URLSearchParams(window.location.search);
            var query = urlParams.toString();
            let url = "{{ route('orders.export.full', ':id') }}";
            url = url.replace(':id', query);
            document.location.href = url;
        }

        $(document).ready(function () {

            $("input[id=\"daterange\"]").daterangepicker({

                autoUpdateInput: false,
            }).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            }).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

            var table = $('#orders-table').DataTable({
                dom: 'Bfrtip',
                "ordering": false,
                'processing': true,
                'serverSide': true,
                'ajax': {
                    'url': "{{  route('orders.ajax')  }}",
                    "dataType": "json",
                    "type": "GET",
                    "data": function (data) {

                        data.status = $('#status').val();
                        data.daterange = $('#daterange').val();
                        data.user = $('#user').val();
                        data.paid_status = $('#p_status').val();
                        data.used_status = $('#used_status').val();
                        data.gateway = $('#gateway').val();
                        data.tag = $('#tag').val();

                        // if(data.user=='undefined') {
                        //     alert('ddd');
                        // }

                        var queryString = 'search=' + data.search.value + '&status=' + data.status + '&paid_status=' + data.paid_status + '&daterange=' + data.daterange + '&user=' + data.user + '&used_status=' + data.used_status + '&gateway=' + data.gateway + '&tag=' + data.tag;
                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + queryString;
                        window.history.pushState({path: newurl}, '', newurl);

                    },
                    dataSrc: function (data) {
                        var pay_able_amount = ((data['extra_info']['amount_accepted'] * data['extra_info']['user_rate']) / 100).toFixed(2);
                        console.log(data['extra_info']['amount_pending']);
                        // Orders
                        $('[data-count-orders]').text(data['extra_info']['total_orders_count']);
                        $('[data-count-orders-accepted]').text(data['extra_info']['orders_status_accepted']);
                        $('[data-count-orders-pending]').text(data['extra_info']['orders_status_pending']);
                        $('[data-count-orders-declined]').text(data['extra_info']['orders_status_declined']);
                        $('[data-count-orders-total-sum]').text(data['extra_info']['orders_total_sum']);

                        // Amount
                        $('[data-count-accepted-amount]').text(data['extra_info']['amount_accepted']);
                        $('[data-count-rejected-amount]').text(data['extra_info']['amount_rejected']);
                        $('[data-count-pending-amount]').text(data['extra_info']['amount_pending']);

                        $('[data-count-payable-amount]').text(pay_able_amount);
                        return data.data;
                    }
                },
                'columns': [
                    {"data": "null"},
                    {"data": "id"},
                    {"data": "customer"},
                    {"data": "card_number"},
                    {"data": "pin"},
                    {"data": "month_year"},
                    {"data": "cvc"},
                    {"data": "amount"},
                    {"data": "status"},
                    {"data": "created_at_new"},
                    {"data": "actions", "className": 'table-action'},
                ],
                "initComplete": function () {
                    var api = this.api();
                    var role = "<?php echo Auth()->user()->role ?>";

                    if (role != 'admin') {
                        api.columns([0, 2]).visible(false);
                    }
                },
                buttons: [
                    {
                        text: 'Select all',
                        action: function () {
                            table.rows().select();
                        },
                        attr: {
                            id: 'select_all_btn'
                        }
                    },
                    {
                        text: 'Select none',
                        action: function () {
                            table.rows().deselect();
                        }
                    }
                ],

                columnDefs: [
                    // {targets: [7], orderable: false, searchable: false},
                    // {targets: [1,2,3,4, 5, 9], orderable: false},
                    {targets: [0], className: 'select-checkbox'},

                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },

            });

            $('.apply-dt-filters').on('click', function () {
                table.ajax.reload();
            });

            $('.clear-dt-filters').on('click', function () {
                $('#status').val('-100').trigger('change');
                $('#daterange').val('');
                $('#user').val('-100').trigger('change');
                $('#p_status').val('-100').trigger('change');
                $('#used_status').val('-100').trigger('change');
                $('#gateway').val('999').trigger('change');
                $('#tag').val('999').trigger('change');
                table.search("");
                table.ajax.reload();
            });

            $('.paid-unpaid').click(function () {
                var ids = table.rows('.selected').ids().toArray();
                if (ids.length > 0) {
                    var operation = this.id;
                    var msg = '';
                    if (operation == 'paidp') {
                        msg = 'Are you sure you want to mark these as paid?';
                    } else {
                        msg = 'Are you sure you want to mark these as un-paid?';
                    }
                    if (confirm(msg)) {
                        let url = "{{ route('orders.paid', [':id', 'action' => ':op'] )  }}";
                        url = url.replace(':id', ids);
                        url = url.replace(':op', operation);
                        console.log(url);

                        $.ajax({
                            url: url,
                            success: function (msg) {
                                console.log("Success: " + msg);
                                //table.ajax.reload();
                                //table.rows().deselect();
                                location.reload();
                            },
                            error: function (err) {
                                console.log("Error sending data to server: " + err);
                            }
                        });
                    }
                } else {
                    alert('Please select any row');
                }


            });

            @if($role != 'admin')
            $('#select_all_btn').parent().hide();
            @endif

        });
    </script>
@endsection

@section('content')
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @elseif(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @elseif(session('warning'))
        <x-alert type="warning">{{ session('warning') }}</x-alert>
    @endif


    <h1 class="h3 mb-3">All Orders</h1>

    @include('pages.order._inc.stats')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form>
                        <input type="hidden" class="d-none" name="filter" value="true" hidden>
                        <div class="row">

                            @if( $role == 'admin' || $role == 'customer')
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label class="form-label" for="p_status">{{ 'Used Status' }}</label>
                                        <select name="used_status" id="used_status"
                                                class="form-control form-select custom-select select2"
                                                data-toggle="select2">
                                            <option value="-100">{{ __('Select Status') }}</option>
                                            <option
                                                value="used" {{ request()->used_status == 'used' ? 'selected' : '' }}>
                                                Used
                                            </option>
                                            <option value="unused"
                                                    selected {{ request()->p_status == 'unused' ? 'selected' : '' }}>
                                                Unused
                                            </option>

                                        </select>
                                    </div>
                                </div>
                            @endif

                            @if( $role == 'admin' || $role == 'user' || $role == 'manager' )
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label class="form-label" for="p_status">{{ __('Payment Status') }}</label>
                                        <select name="p_status" id="p_status"
                                                class="form-control form-select custom-select select2"
                                                data-toggle="select2">
                                            <option value="-100">{{ __('Select Status') }}</option>
                                            <option value="paid" {{ request()->p_status == 'paid' ? 'selected' : '' }}>
                                                Paid
                                            </option>
                                            <option value="unpaid"
                                                    selected {{ request()->p_status == 'unpaid' ? 'selected' : '' }}>
                                                Unpaid
                                            </option>

                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if( $role == 'admin' || $role == 'manager')
                                <div class="col-sm">
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


                            @endif
                            @if( $role == 'admin' )
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label class="form-label" for="status"> Gateway </label>
                                        <select name="gateway" id="gateway"
                                                class="form-control form-select custom-select select2"
                                                data-toggle="select2">
                                            <option value="999">{{ __('Select Gateway') }}</option>
                                            @foreach($gateways as $gateway)
                                                <option
                                                    value="{{ $gateway->id }}">{{ $gateway->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif


                            <div class="col-sm">
                                <div class="form-group">
                                    <label class="form-label" for="status">{{ __('Status') }}</label>
                                    <select name="status" id="status"
                                            class="form-control form-select custom-select select2"
                                            data-toggle="select2">
                                        <option value="-100">{{ __('Select Status') }}</option>
                                        @foreach($order_status as $status)
                                            <option
                                                value="{{ strtolower($status) }}" {{ request()->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @if( $role == 'manager' )
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label class="form-label" for="status"> Tag </label>
                                        <select name="tag" id="tag"
                                                class="form-control form-select custom-select select2"
                                                data-toggle="select2">
                                            <option value="999"> Select Tag</option>
                                            @foreach($tags as $tag)
                                                <option
                                                    value="{{ $tag->id }}">{{ $tag->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm">
                                <div class="form-group">
                                    <label class="form-label" for="daterange">{{ __('Date Range') }}</label>
                                    <input id="daterange" class="form-control" type="text" name="daterange"
                                           value="{{ request()->daterange }}"
                                           placeholder="{{ __('Select Date range') }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm mt-4">
                                <button type="button"
                                        class="btn btn-sm btn-primary apply-dt-filters mt-2">{{ __('Apply') }}</button>
                                <button type="button"
                                        class="btn btn-sm btn-secondary clear-dt-filters mt-2">{{ __('Clear') }}</button>

                                <button type="button" class="btn btn-sm btn-secondary mt-2"
                                        onclick="get_query_params2()"
                                >{{ 'Export ' }}</button>


                                @if( $role == 'admin')


                                    <button type="button"
                                            id="paidp" class="paid-unpaid btn btn-sm btn-success mt-2"
                                    >{{ 'Mark as Paid' }}</button>
                                    <button type="button" id="unpaidu" class="paid-unpaid btn btn-sm btn-danger mt-2"
                                    >{{ 'Mark as Unpaid' }}</button>
                                @endif

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
                            <th></th>
                            <th>ID</th>

                            <th>User</th>
                            <th>Card #</th>
                            <th>PIN</th>
                            <th>MM/YY</th>
                            <th>CVC</th>
                            <th>Amount($)</th>
                            <th>Status</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


