@extends('layouts.app')

@section('title', 'Configure Settings')

@section('content')

    <h1 class="h3 mb-3">Business Hours</h1>

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

                    <form method="post" action="{{ route('settings.update') }}">
                        @csrf
                        <div class="row">

                            <div class="col-6">

                                <div class="form-group">

                                    <label for="year">Status</label>
                                    <div>
                                        <label class="switch">
                                            <input type="checkbox" name="open_status" @if($status == '1') checked @endif>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <p>Toggle the status of order placing form</p>

                                </div>

                            </div>


                        </div>
                        <div class="form-group">
                            <label for="month">Message Title</label>
                            <input
                                class="form-control form-control-lg mb-3 @error('business_msg_title') is-invalid @enderror"
                                type="text"
                                name="business_msg_title"
                                placeholder="Enter message title"
                                value="{{ old('business_msg_title', $msg_title) }}"
                            />
                            @error('business_msg_title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="month">Message Description</label>
                            <input
                                class="form-control form-control-lg mb-3 @error('business_msg') is-invalid @enderror"
                                type="text"
                                name="business_msg"
                                placeholder="Enter message title"
                                value="{{ old('business_msg', $msg_desc) }}"
                            />
                            @error('business_msg')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary">Save Settings
                            </button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection
