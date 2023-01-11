@extends('layouts.app')

@section('title', __('Add User'))
@section('scripts')
{{--    <script>--}}

{{--        var asdas ='{{ json_encode($users) }}';--}}
{{--        $.each(asdas, function(index, item) {--}}
{{--            console.log(index);--}}
{{--            // do something with `item` (or `this` is also `item` if you like)--}}
{{--        });--}}

{{--    </script>--}}
@endsection
@section('content')



    <h1 class="h3 mb-3">{{ __('Add User') }}</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    @if(session('success'))
                        <x-alert type="success">{{ session('success') }}</x-alert>
                    @endif

                    <form method="post" action="{{ route('users.store') }}">
                        @csrf

                        <x-input type="text" name="name" label="{{ __('Name') }}" placeholder="{{ __('Enter your name') }}"></x-input>
                        <x-input type="email" name="email" label="{{ __('Email') }}" placeholder="{{ __('Enter your email') }}"></x-input>
                        <x-input type="password" name="password" label="{{ __('Password') }}" placeholder="{{ __('Enter your password') }}"></x-input>
                        <x-input type="password" label="{{ __('Confirm Password') }}" name="password_confirmation" placeholder="{{ __('Enter your password again') }}"></x-input>

                        <div class="form-group">
                            <label for="role">{{ __('Role') }}</label>
                            <select id="role" class="form-control select2 @error('role') is-invalid @enderror" name="role" data-toggle="select2">
                                <option value="user" @if(old('role') == 'user') selected @endif>{{ __('User') }}</option>
                                <option value="customer" @if(old('role') == 'customer') selected @endif>{{ __('Customer') }}</option>
                                <option value="manager" @if(old('role') == 'manager') selected @endif>{{ __('Manager') }}</option>
                                <option value="admin" @if(old('role') == 'admin') selected @endif>{{ __('Admin') }}</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="parent"> Assign to </label>
                            <select id="parent" class="form-control select2" name="parent" data-toggle="select2">

                                <option value="0" selected> Select </option>
                               @foreach($users as $user)
                                    <option value="{{ $user->id }}"> {{ $user->name }} - {{ $user->email }} </option>
                                @endforeach

                            </select>

                        </div>

                        <button type="submit" class="btn btn-lg btn-primary">{{ __('Add User') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

