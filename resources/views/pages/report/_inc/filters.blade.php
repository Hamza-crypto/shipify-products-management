

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form>
                    <input type="hidden" class="d-none" name="filter" value="true" hidden>
                    <div class="row">

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="daterange"> Select Report</label>
                                <select name="date" id="date"
                                        class="form-control form-select custom-select select2"
                                        data-toggle="select2">

                                    <option value="1001" > Select </option>
                                    <option value="0" > Today </option>
                                    <option value="1" > Yesterday </option>
                                    <option value="6" > Last 7 days </option>
                                    <option value="60" > Last 30 days </option>
                                    <option value="1000"> Custom Range </option>
                                </select>
                            </div>

                        </div>

                        <div class="d-none col-sm" id="date_range_div2">
                            <div class="form-group">
                                <label class="form-label" for="daterange"> Custom Range</label>
                                <input id="daterange" class="form-control" type="text" name="daterange"
                                       value="{{ request()->daterange }}"
                                       placeholder="{{ __('Select Date range') }}"/>
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="status"> Gateway </label>
                                <select name="gateway" id="gateway"
                                        class="form-control form-select custom-select select2"
                                        data-toggle="select2">
                                    <option value="999">{{ __('Select Gateway') }}</option>
                                    @foreach($gateways as $gateway)
                                        <option
                                            value="{{ $gateway->id }}" >{{ $gateway->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="status"> User </label>
                                <select name="users" id="user"
                                        class="form-control form-select custom-select select2"
                                        data-toggle="select2">
                                    <option value="0">{{ __('Select User') }}</option>
                                    @foreach($users as $user)
                                        <option
                                            value="{{ $user->id }}" {{ request()->user == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="status"> Manager </label>
                                <select name="manager" id="manager"
                                        class="form-control form-select custom-select select2"
                                        data-toggle="select2">
                                    <option value="0"> Select Manager </option>
                                    @foreach($managers as $manager)
                                        <option
                                            value="{{ $manager->id }}" {{ request()->user == $manager->id ? 'selected' : '' }}>{{ $manager->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="status"> Sub users </label>
                                <select name="sub_users" id="sub_users"
                                        class="form-control form-select custom-select select2"
                                        data-toggle="select2">

                                </select>
                            </div>
                        </div>



                    </div>
                    <div class="row">
                        <div class="col-sm mt-4">
                            <button type="button"
                                    class="btn btn-sm btn-primary apply-dt-filters mt-2"
                                    onclick="get_query_params()"> Apply </button>


                            <button type="button"
                                    class="btn btn-sm btn-secondary clear-dt-filters mt-2"> Clear </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
