@php
    $api_key = old("api_key", $user->paxful_api_key() ?? '');
    $api_secret = old("api_secret", $user->paxful_api_secret() ?? '');


@endphp

<div class="tab-pane fade @if($tab == 'paxful2') show active @endif" id="paxful2" role="tabpanel">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Paxful APIs</h5>
        </div>
        <div class="card-body">

            <form method="post" action="{{ route('api.store', $user->id) }}">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="usdt">API Key</label>
                    <input
                        class="form-control form-control-lg mb-3 @error('api_key') is-invalid @enderror"
                        type="text"
                        name="api_key"
                        placeholder="Enter your Paxful API key"
                        value="{{ $api_key }}"
                        required

                    />

                    @error('api_key')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="btc">API Secret</label>
                    <input
                        class="form-control form-control-lg mb-3 @error('api_secret') is-invalid @enderror"
                        type="password"
                        name="api_secret"
                        placeholder="Enter your Paxful API secret"
                        value="{{ $api_secret }}"
                        required

                    />

                    @error('api_secret')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update API Credentials</button>

            </form>
        </div>
    </div>
</div>
