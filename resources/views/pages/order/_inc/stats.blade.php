<div class="row">
    @php
        $user = Auth()->user();
        $role = $user->role;
    @endphp

    <div class="col-12 col-sm-6 col-xxl d-flex">
        <div class="card flex-fill">
            <div class="card-body py-4">
                <div class="media">
                    <div class="media-body">

                        <h3 class="mb-2">
                            <span data-count-orders> 0 </span> Orders
                        </h3>

                        <p class="mb-2">Total Orders</p>
                        <div class="mb-0">
                                <span class="badge badge-soft-success mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                      @if( $role == 'user' )
                                        {{ Auth()->user()->accepted_orders->count()  }} Accepted
                                    @else
                                        <span data-count-orders-accepted> 0 </span> Accepted
                                    @endif
                                </span>
                            <span class="badge badge-soft-danger mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                @if( $role == 'user' )
                                    {{ Auth()->user()->rejected_orders->count()  }} Rejected
                                @else
                                    <span data-count-orders-declined> 0 </span> Rejected
                                @endif

                                </span>
                            <span class="badge badge-soft-warning mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                @if( $role == 'user' )
                                    {{ Auth()->user()->pending_orders->count()  }} Pending
                                @else
                                    <span data-count-orders-pending> 0 </span> Pending
                                @endif

                                </span>
                        </div>
                    </div>
                    <div class="d-inline-block ml-3">
                        <div class="stat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-shopping-bag align-middle text-danger">
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

                        <h3 class="mb-2">$<span data-count-orders-total-sum> 0 </span>

                            <!--@if($user->payable_section_visibility_status() == 1 )-->
                            <!--    (Payable $<span data-count-payable-amount> 0 </span>)-->
                            <!--@endif-->
                            
                              @if($role == 'admin' )
                                (Payable $<span data-count-payable-amount> 0 </span>)
                            @endif

                        </h3>

                        <p class="mb-0">Total Amount</p>
                        <div class="mb-0">
                                <span class="badge badge-soft-success mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>

                                        $<span data-count-accepted-amount> 0 </span> Accepted

                                </span>
                            <span class="badge badge-soft-danger mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>

                                   $<span data-count-rejected-amount> 0 </span> Rejected


                                </span>
                            <span class="badge badge-soft-warning mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>

                                    $<span data-count-pending-amount> 0 </span> Pending

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


    <div class="col-12 col-sm-6 col-xxl d-flex">
        <div class="card flex-fill">
            <div class="card-body py-4">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">

                        <h3 class="mb-2"><span> {{ $average }} Minutes</span>
                        </h3>

                        <p class="mb-0">Average Acceptance Rate</p>

                    </div>
                    <div class="d-inline-block ms-3">
                        <div class="stat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-clock align-middle">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
