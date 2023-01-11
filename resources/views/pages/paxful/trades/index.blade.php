@extends('layouts.app')

@section('title', __('Trades'))

@section('scripts')
    <script>
        function expand_row(thiss) {

            $('.inner-row').addClass('d-none');

            console.log(thiss.id);
            var id = thiss.id.replace('parent', 'child');

            $('#'+id).toggleClass("d-none");

        }
    </script>

@endsection

@section('content')
    <!--<h1 class="h3 mb-3">{{ 'Active Trades' }}</h1>-->

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @endif
    <div id="app">
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

                            {{-- api-route="{{ route('api.trades_active2') }}" --}}
                        <active-trades
                            placeholder_img="{{ asset('assets/img/avatar.png') }}"
                            open_status="{{ $open  }}"
                            msg_title="{{ $msg_title  }}"
                            msg_desc="{{ $msg  }}"
                        >

                        </active-trades>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="container-fluid p-0">-->
    <!--    <div class="row h-100">-->
    <!--        <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">-->
    <!--            <div class="d-table-cell align-middle">-->

    <!--                <div class="text-center">-->

    <!--                    <p class="h1">Maintenance time</p>-->
    <!--                    <p class="h2 fw-normal mt-3 mb-4">We will be available shortly after maintenance.</p>-->

    <!--                </div>-->

    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->

    <!--</div>-->

@endsection
