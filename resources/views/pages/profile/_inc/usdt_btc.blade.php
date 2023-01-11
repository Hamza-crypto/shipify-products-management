@php
    $usdt = old("usdt", $user->usdt_address() ?? '');
    $btc = old("btc", $user->btc_address() ?? '');
    $trc = old("btc", $user->trc_address() ?? '');
    $rate= old("rate", $user->rate() ?? '');

@endphp

<div class="tab-pane fade @if($tab == 'wallet') show active @endif" id="wallet" role="tabpanel">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Wallet Info</h5>
        </div>
        <div class="card-body">

            <form method="post" action="{{ route('wallet.update', $user->id) }}">
                @csrf
                @method('PATCH')


                <div class="form-group">
                    <label for="usdt">USDT</label>
                    <input
                        class="form-control form-control-lg mb-3 @error('usdt') is-invalid @enderror"
                        type="text"
                        name="usdt"
                        placeholder="Enter your USDT wallet address"
                        @if ( !empty( $usdt ))
                        disabled
                        value="{{ $usdt }}"
                        @endif
                    />

                    @error('usdt')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="btc">BTC</label>
                    <input
                        class="form-control form-control-lg mb-3 @error('btc') is-invalid @enderror"
                        type="text"
                        name="btc"
                        placeholder="Enter your BTC wallet address"
                        @if ( !empty( $btc ))
                        disabled
                        value="{{ $btc }}"
                        @endif
                    />

                    @error('btc')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="btc">USDT TRC-20</label>
                    <input
                        class="form-control form-control-lg mb-3 @error('trc') is-invalid @enderror"
                        type="text"
                        name="trc"
                        placeholder="Enter USDT TRC-20"
                        @if ( !empty( $trc ))
                        disabled
                        value="{{ $trc }}"
                        @endif
                    />

                    @error('trc')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <x-input
                    type="number"
                    label="Rate(%)"
                    step="0.01"
                    name="rate"
                    placeholder="Enter your BTC wallet address"
                    :value="$rate"
                    disabled

                ></x-input>

                @if ( !( !empty( $usdt ) && !empty( $btc) && !empty( $trc )  ))
                    <button type="submit" class="btn btn-primary">Update wallet info</button>
                @endif

            </form>
        </div>
    </div>
</div>
