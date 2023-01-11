@extends('layouts.app')

@section('title', __('Chat'))

@section('scripts')
    <script>

    </script>
@endsection

@section('content')
    <h1 class="h3 mb-3"> All Messages</h1>

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @endif



    <div class="card" id="app">
        <trade-messages-completed hash="{{ $hash }}" author="{{ $author }}"></trade-messages-completed>
    </div>
@endsection
