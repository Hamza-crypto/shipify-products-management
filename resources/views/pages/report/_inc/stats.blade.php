<div class="row">
    <div class="col-12 col-sm-6 col-xxl d-flex">
        <div class="card flex-fill">
            <div class="card-body py-4">
                <div class="media">
                    <div class="media-body">

                        <h3 class="mb-2">
                            <span> {{ $data['total_orders_count'] }} </span> Orders
                        </h3>

                        <p class="mb-2">Total Orders</p>
                        <div class="mb-0">
                                <span class="badge badge-soft-success mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                        <span> {{ $data['total_accepted_orders_count'] }} </span> Accepted
                                </span>
                            <span class="badge badge-soft-danger mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>

                                        <span> {{ $data['total_rejected_orders_count'] }} </span> Rejected


                                </span>
                            <span class="badge badge-soft-warning mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>

                                        <span>  {{ $data['total_pending_orders_count'] }}  </span> Pending


                            </span>

                            <span class="badge badge-soft-secondary mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>

                                        <span>  {{ $data['total_canceled_orders_count'] }}  </span> Canceled


                            </span>
                        </div>
                    </div>
                    <div class="d-inline-block ml-3">
                        <div class="stat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="feather feather-shopping-bag align-middle text-danger">
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

                        <h3 class="mb-2">$<span> {{ $data['total_volume'] }} </span>

                        </h3>

                        <p class="mb-0">Total Volume</p>
                        <div class="mb-0">
                                <span class="badge badge-soft-success mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>

                                        $<span> {{ $data['total_accepted_volume'] }} </span> Accepted

                                </span>
                            <span class="badge badge-soft-danger mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>

                                   $<span> {{ $data['total_decline_volume'] }} </span> Rejected


                            </span>
                            <span class="badge badge-soft-warning mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>

                                    $<span>  {{ $data['total_pending_volume'] }} </span> Pending

                            </span>

                            <span class="badge badge-soft-secondary mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>

                                    $<span>  {{ $data['total_canceled_volume'] }} </span> Canceled

                            </span>
                        </div>
                    </div>
                    <div class="d-inline-block ms-3">
                        <div class="stat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="feather feather-dollar-sign align-middle text-success">
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
                <div class="media">
                    <div class="media-body">

                        <h3 class="mb-2">$<span> {{ $data['total_volume'] }} </span></h3>

                        <p class="mb-2">Acceptance Rate</p>

                        <div class="mb-0">
                                <span class="badge badge-soft-success mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                        <span> {{ $data['total_orders_count'] == 0 ? 0 : round(100 * $data['total_accepted_orders_count'] / $data['total_orders_count'] , 2)   }} </span> % Accepted
                                </span>
                            <span class="badge badge-soft-danger mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>

                                        <span> {{ $data['total_orders_count'] == 0 ? 0 : round(100 * $data['total_rejected_orders_count'] / $data['total_orders_count'] ,2) ?? 0 }} </span> % Rejected


                                </span>
                            <span class="badge badge-soft-warning mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>

                                        <span>  {{ $data['total_orders_count'] == 0 ? 0 : round(100 * $data['total_pending_orders_count'] / $data['total_orders_count'], 2) ?? 0 }}  </span> % Pending


                            </span>

                            <span class="badge badge-soft-secondary mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>

                                        <span>  {{ $data['total_orders_count'] == 0 ? 0 : round(100 * $data['total_canceled_orders_count'] / $data['total_orders_count'],2) ?? 0 }}  </span> % Canceled


                            </span>
                        </div>
                    </div>
                    <div class="d-inline-block ml-3">
                        <div class="stat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="feather feather-shopping-bag align-middle text-danger">
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
    {{--    <div class="col-12 col-sm-6 col-xxl d-flex">--}}
    {{--        <div class="card flex-fill">--}}
    {{--            <div class="card-body py-4">--}}
    {{--                <div class="d-flex align-items-start">--}}
    {{--                    <div class="flex-grow-1">--}}

    {{--                        <h3 class="mb-2">$<span> {{ $data['g1_total_volume'] }} </span>--}}

    {{--                        </h3>--}}

    {{--                        <p class="mb-0">Total Volume </p>--}}
    {{--                        <p class="mb-0">(Master Gateway)</p>--}}
    {{--                        <div class="mb-0">--}}
    {{--                                <span class="badge badge-soft-success mr-2">--}}
    {{--                                    <i class="mdi mdi-arrow-bottom-right"></i>--}}

    {{--                                        $<span> {{ $data['g1_accepted_orders'] }} </span> Accepted--}}

    {{--                                </span>--}}
    {{--                            <span class="badge badge-soft-danger mr-2">--}}
    {{--                                    <i class="mdi mdi-arrow-bottom-right"></i>--}}

    {{--                                   $<span> {{ $data['g1_decline_orders'] }} </span> Rejected--}}


    {{--                                </span>--}}
    {{--                            <span class="badge badge-soft-warning mr-2">--}}
    {{--                                    <i class="mdi mdi-arrow-bottom-right"></i>--}}

    {{--                                    $<span>  {{ $data['g1_pending_orders'] }} </span> Pending--}}

    {{--                                </span>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="d-inline-block ms-3">--}}
    {{--                        <div class="stat">--}}
    {{--                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"--}}
    {{--                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"--}}
    {{--                                 stroke-linejoin="round"--}}
    {{--                                 class="feather feather-dollar-sign align-middle text-success">--}}
    {{--                                <line x1="12" y1="1" x2="12" y2="23"></line>--}}
    {{--                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>--}}
    {{--                            </svg>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}


    {{--        <div class="col-12 col-sm-6 col-xxl d-flex">--}}
    {{--            <div class="card flex-fill">--}}
    {{--                <div class="card-body py-4">--}}
    {{--                    <div class="d-flex align-items-start">--}}
    {{--                        <div class="flex-grow-1">--}}

    {{--                            <h3 class="mb-2"><span> {{ $average }} Minutes</span>--}}
    {{--                            </h3>--}}

    {{--                            <p class="mb-0">Average Acceptance Rate</p>--}}

    {{--                        </div>--}}
    {{--                        <div class="d-inline-block ms-3">--}}
    {{--                            <div class="stat">--}}
    {{--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"--}}
    {{--                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"--}}
    {{--                                     stroke-linejoin="round" class="feather feather-clock align-middle">--}}
    {{--                                    <circle cx="12" cy="12" r="10"></circle>--}}
    {{--                                    <polyline points="12 6 12 12 16 14"></polyline>--}}
    {{--                                </svg>--}}

    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}


</div>



