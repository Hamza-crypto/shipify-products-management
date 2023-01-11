<div class="row">

    @php
        $role = Auth()->user()->role
    @endphp


    <div class="col-12 col-sm-6 col-xxl d-flex">
        <div class="card illustration flex-fill">
            <div class="card-body p-0 d-flex flex-fill">
                <div class="row g-0 w-100">
                    <div class="col-6">
                        <div class="illustration-text p-3 m-1">
                            <h4 class="illustration-text">Welcome Back, {{ Auth() ->user()->name }}</h4>
                            <p class="mb-0">{{ env('APP_NAME') }} Dashboard</p>
                        </div>
                    </div>
                    <div class="col-6 align-self-end text-end">
                        <img src="{{ asset('assets/img/customer-support.png') }}" alt="Customer Support"
                             class="img-fluid illustration-img">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if( $role == 'admin' )
        <div class="col-12 col-sm-6 col-xxl d-flex">
            <div class="card flex-fill">
                <div class="card-body py-4">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-2"> {{ \App\Models\User::count() }} Users </h3>
                            <p class="mb-2">Total Users</p>
                            <div class="mb-0">
                                <span class="badge badge-soft-success mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                    {{ \App\Models\User::where('role', 'customer')->count()  }} Customers
                                </span>
                                <span class="badge badge-soft-success mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                    {{ \App\Models\User::where('role', 'user')->count()  }} User
                                </span>
                                <span class="badge badge-soft-success mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                    {{ \App\Models\User::where('role', 'admin')->count()  }} Admins
                                </span>
                            </div>
                        </div>
                        <div class="d-inline-block ml-3">
                            <div class="stat">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-users align-middle text-success">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="col-12 col-sm-6 col-xxl d-flex">
        <div class="card flex-fill">
            <div class="card-body py-4">
                <div class="media">
                    <div class="media-body">
                        @if( $role == 'user' )
                            <h3 class="mb-2"> {{ Auth()->user()->user_orders->count() }} Orders </h3>
                        @else
                            <h3 class="mb-2"> {{ \App\Models\Order::count() }} Orders </h3>
                        @endif

                        <p class="mb-2">Total Orders</p>
                        <div class="mb-0">
                                <span class="badge badge-soft-success mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                      @if( $role == 'user' )
                                        {{ Auth()->user()->accepted_orders()->count()  }} Accepted
                                    @else
                                        {{ \App\Models\Order::where('status', 'accepted')->count()  }} Accepted
                                    @endif
                                </span>
                            <span class="badge badge-soft-danger mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                @if( $role == 'user' )
                                    {{ Auth()->user()->rejected_orders()->count()  }} Rejected
                                @else
                                    {{ \App\Models\Order::where('status', 'declined')->count()  }} Rejected
                                @endif

                                </span>
                            <span class="badge badge-soft-warning mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                @if( $role == 'user' )
                                    {{ Auth()->user()->pending_orders()->count()  }} Pending
                                @else
                                    {{ \App\Models\Order::where('status', 'pending')->count()  }} Pending
                                @endif

                                </span>
                        </div>
                    </div>
                    <div class="d-inline-block ml-3">
                        <div class="stat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag align-middle text-danger">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xxl d-flex">
        <div class="card flex-fill">
            <div class="card-body py-4">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        @if( $role == 'user' )
                            <h3 class="mb-2">${{ round(Auth()->user()->user_orders->sum('amount'), 2)  }}</h3>
                        @else
                            <h3 class="mb-2">${{ round(\App\Models\Order::sum('amount'),2)  }}</h3>
                        @endif


                        <p class="mb-0">Total Amount</p>
                            <div class="mb-0">
                                <span class="badge badge-soft-success mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                      @if( $role == 'user' )
                                        ${{ round (Auth()->user()->accepted_orders->sum('amount') ,2)  }} Accepted
                                    @else
                                        ${{ round(\App\Models\Order::where('status', 'accepted')->sum('amount') ,2)  }} Accepted
                                    @endif
                                </span>
                                <span class="badge badge-soft-danger mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                @if( $role == 'user' )
                                        ${{ round( Auth()->user()->rejected_orders->sum('amount')  ,2)  }} Rejected
                                    @else
                                        ${{ round( \App\Models\Order::where('status', 'declined')->sum('amount')  ,2) }} Rejected
                                    @endif

                                </span>
                                <span class="badge badge-soft-warning mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                @if( $role == 'user' )
                                        ${{ round(Auth()->user()->pending_orders->sum('amount'), 2)   }} Pending
                                    @else
                                        ${{ round( \App\Models\Order::where('status', 'pending')->sum('amount') ,2)  }} Pending
                                    @endif

                                </span>
                            </div>
                    </div>
                    <div class="d-inline-block ms-3">
                        <div class="stat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-dollar-sign align-middle text-success">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
