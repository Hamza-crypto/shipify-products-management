@php
    $name = old("name", $user->name ?? '');

@endphp

<div class="tab-pane fade @if($tab == 'gateway') show active @endif" id="gateway" role="tabpanel">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Assign Gateway</h5>
        </div>
        <div class="card-body">

            <form method="post" action="{{ route('gateway.update', $user->id) }}">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="gateway">Select Gateway</label>
                    <select name="gateway_id" id="gateway"
                            class="form-control form-select custom-select select2"
                            data-toggle="select2">
                        <option value="0">Select</option>
                        @foreach($gateways as $gateway)
                            <option
                                value="{{ $gateway->id }}" @if( $gateway->id == $user->gateway()) selected  @endif>{{ $gateway->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update gateway</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Assign this user to</h5>
        </div>
        <div class="card-body">

            <form method="post" action="{{ route('parent.update', $user->id) }}">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="parent">Select Manager</label>
                    <select name="parent" id="parent"
                            class="form-control form-select custom-select select2"
                            data-toggle="select2">
                        <option value="0">Select</option>
                        @foreach($managers as $manager)
                            <option
                                value="{{ $manager->id }}" @if( $manager->id == $parent_id) selected  @endif>{{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
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

@include('pages.users._inc.usdt_btc')


