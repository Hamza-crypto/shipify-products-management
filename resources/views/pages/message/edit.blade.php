@extends('layouts.app')

@section('title', 'Edit Gateway')
@php
    $role = Auth()->user()->role;
@endphp

@section('scripts')
    <script>
        $(document).ready(function () {
            var type = $('#type').val();
            if (type == 'favorite') {
                $('#trigger_at').attr('disabled', false);
            }


            $("#type").change(function () {

                var type = $('#type').val();
                console.log(type);
                if (type == 'auto') {
                    $('#trigger_at').attr('disabled', false);
                } else {
                    $('#trigger_at').val('0').trigger('change');
                    $('#trigger_at').attr('disabled', true);
                }
            });

        });
    </script>
@endsection
@section('content')

    <h1 class="h3 mb-3">Edit Message</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <x-alert type="success">{{ session('success') }}</x-alert>
                    @endif

                    <form method="post" action="{{ route('messages.update' ,$message->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="role"> Type </label>
                                    <select id="type"
                                            class="form-control form-control-lg select2"
                                            name="type" data-toggle="select2">

                                        <option value="favorite" selected>Favorite</option>
                                        <option value="auto" @if(old('type') == 'auto' || $message->type == 'auto') selected @endif>Auto</option>


                                    </select>
                                    @error('role')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="role"> Trigger at </label>
                                    <select id="trigger_at"
                                            class="form-control form-control-lg select2"
                                            name="trigger_at" data-toggle="select2">

                                        <option value="0"  selected> Select</option>
                                        <option value="trade_start" @if(old('trigger_at') == 'trade_start' || $message->trigger_at == 'trade_start') selected @endif>Trade start</option>
                                        <option value="release_coin" @if(old('trigger_at') == 'release_coin' || $message->trigger_at == 'release_coin') selected @endif>Release coin</option>


                                    </select>
                                    @error('role')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="text">Message text</label>
                                    <textarea
                                        class="form-control @error('body') is-invalid @enderror"
                                        name="body"
                                        rows="6"
                                        placeholder="Enter your message text here..."
                                    >{{ old('body', $message->body) }}</textarea>

                                    @error('body')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary">Update Message</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

