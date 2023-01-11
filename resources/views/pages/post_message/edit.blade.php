@extends('layouts.app')

@section('title', 'Edit Gateway')
@php
    $role = Auth()->user()->role;
@endphp

@section('scripts')
    <script>
        $(document).ready(function () {
            $("input[name=\"datesingle\"]").daterangepicker({
                singleDatePicker: true,
                timePicker: true,
                showDropdowns: true,

                locale: {
                    format: "Y-MM-DD HH:mm"
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

                    <form method="post" action="{{ route('post_messages.update' ,$postMessage->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="role"> Group </label>
                                    <select id="type"
                                            class="form-control form-control-lg select2 @error('group') is-invalid @enderror"
                                            name="group" data-toggle="select2">

                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach

                                    </select>
                                    @error('group')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="role"> Post at </label>
                                    <input class="form-control" type="text" name="datesingle" value="{{$postMessage->post_at}}">
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
                                        rows="2"
                                        placeholder="Enter your message text here..."
                                    >{{ $postMessage->body }}</textarea>

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

