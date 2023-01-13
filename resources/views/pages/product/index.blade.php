@extends('layouts.app')

@section('title', 'Products')

@section('scripts')
    <script>

        function get_query_params2() {
            var urlParams = new URLSearchParams(window.location.search);
            var query = urlParams.toString();
            let url = "{{ route('products.export', ':id') }}";
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
//p = pagination
// f = find
            var table = $('#products-table').DataTable({
                dom: 'rtip',
                "ordering": false,
                'processing': true,
                'serverSide': true,
                'ajax': {
                    'url': "{{  route('products.ajax')  }}",
                    "dataType": "json",
                    "type": "GET",
                    "data": function (data) {

                        data.store = $('#store').val();
                        data.status = $('#status').val();

                        var queryString = '&status=' + data.status + '&store=' + data.store;
                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + queryString;
                        window.history.pushState({path: newurl}, '', newurl);

                    },
                    dataSrc: function (data) {
                        return data.data;
                    }
                },
                'columns': [
                    {"data": "id"},
                    {"data": "title"},
                    {"data": "product_id"},
                    {"data": "sku"},
                    {"data": "upc"},
                    {"data": "status"},
                    {"data": "num_of_images"}
                ],
                "initComplete": function () {
                    var api = this.api();

                }

            });

            $('.apply-dt-filters').on('click', function () {
                table.ajax.reload();
            });

            $('.clear-dt-filters').on('click', function () {
                $('#status').val('0').trigger('change');
                $('#store').val('1').trigger('change');

                table.search("");
                table.ajax.reload();
            });



            $('#select_all_btn').parent().hide();


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


    <h1 class="h3 mb-3">All Products</h1>

{{--    @include('pages.order._inc.stats')--}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form>
                        <input type="hidden" class="d-none" name="filter" value="true" hidden>
                        <div class="row">

                                <div class="col-sm">
                                    <div class="form-group">
                                        <label class="form-label" for="store">{{ 'Store' }}</label>
                                        <select name="store" id="store"
                                                class="form-control form-select custom-select select2"
                                                data-toggle="select2">
                                            @foreach($stores as $store)
                                                <option value="{{ $store->id }}" {{ $store->id == request()->store ? 'selected' : '' }}>{{ $store->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm">
                                    <div class="form-group">
                                        <label class="form-label" for="status"> Status </label>
                                        <select name="status" id="status"
                                                class="form-control form-select custom-select select2"
                                                data-toggle="select2">
                                            <option value="0" selected> Whitelisted </option>
                                            <option value="1"> Blacklisted </option>

                                        </select>
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

                    <table id="products-table" class="table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Product ID</th>
                            <th>SKU</th>
                            <th>UPC</th>
                            <th>Status</th>
                            <th>Images</th>
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


