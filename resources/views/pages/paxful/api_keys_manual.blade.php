<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Missing API Key parameters</h5>
    </div>
    <div class="card-body">
        <p class="card-text">Your paxful API key and secret is not set yet, they are required to handle
            your paxful offers from here.
            <br> <a href="https://paxful.com/account/developer">Click here to get your Paxful API</a>
            <br> Click on below button to set your API key.</p>
        <a href="{{ route('profile.index', 'paxful2') }}" class="btn btn-primary">Set API key here</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <a href="https://paxful.com/account/developer">1. Create API Keys    </a>

        </h5>
    </div>
    <div class="card-body">
        <img src="{{ asset('assets/img/api.png') }}">
    </div>
</div>

{{--<div class="card">--}}
{{--    <div class="card-header">--}}
{{--        <h5 class="card-title mb-0">--}}
{{--         2. Copy API Keys--}}

{{--        </h5>--}}
{{--    </div>--}}
{{--    <div class="card-body">--}}
{{--        <img src="{{ asset('assets/img/2.png') }}">--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div class="card">--}}
{{--    <div class="card-header">--}}
{{--        <h5 class="card-title mb-0">--}}
{{--         3. Activate application--}}

{{--        </h5>--}}
{{--    </div>--}}
{{--    <div class="card-body">--}}
{{--        <img src="{{ asset('assets/img/3.png') }}">--}}
{{--    </div>--}}
{{--</div>--}}
