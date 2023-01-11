@php
    $role = Auth()->user()->role;
@endphp
<nav id="sidebar" class="sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('dashboard.index') }}">
            <span class="align-middle me-3">{{ env("APP_NAME") }}</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                General
            </li>
            <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard.index') }}">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-header">
                Order Requests
            </li>
            <li class="sidebar-item {{ request()->is('orders') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('orders.index') }}">
                    <i class="align-middle" data-feather="grid"></i>
                    <span class="align-middle">All Orders</span>
                </a>
            </li>
            @if( $role == 'user')
                <li class="sidebar-item {{ request()->is('orders/create') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('orders.create') }}">
                        <i class="align-middle" data-feather="plus-square"></i>
                        <span class="align-middle">Add New Order</span>
                    </a>
                </li>


            @endif

            @if( $role == 'admin')

                <li class="sidebar-item {{ request()->is('order/store/card') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('order.add_store') }}">
                        <i class="align-middle" data-feather="plus-square"></i>
                        <span class="align-middle">Add Store Card</span>
                    </a>
                </li>

                <li class="sidebar-header">
                    Manage
                </li>
                <li class="sidebar-item {{ request()->is('users*') ? 'active' : '' }} ">
                    <a data-target="#users" data-toggle="collapse" class="sidebar-link {{ request()->is('users/*') ? 'collapsed' : '' }}">
                        <i class="align-middle" data-feather="users"></i>
                        <span class="align-middle">Users</span>
                    </a>
                    <ul id="users"
                        class="sidebar-dropdown list-unstyled collapse {{ request()->is('users*') ? 'show' : '' }}"
                        data-parent="#sidebar">

                        <li class="sidebar-item {{ request()->is('users') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('users.index') }}">
                                <i class="align-middle" data-feather="users"></i>
                                <span class="align-middle">All Users</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('users/create') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('users.create') }}">
                                <i class="align-middle" data-feather="user-plus"></i>
                                <span class="align-middle">Add New User</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item {{ request()->is('bins*') ? 'active' : '' }} ">
                    <a data-target="#bins" data-toggle="collapse" class="sidebar-link collapsed">
                        <i class="align-middle" data-feather="credit-card"></i>
                        <span class="align-middle">BINS</span>
                    </a>
                    <ul id="bins"
                        class="sidebar-dropdown list-unstyled collapse {{ request()->is('bins*') ? 'show' : '' }}"
                        data-parent="#sidebar">

                        <li class="sidebar-item {{ request()->is('bins') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('bins.index') }}">
                                <i class="align-middle" data-feather="credit-card"></i>
                                <span class="align-middle">All BINS</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('bins/create') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('bins.create') }}">
                                <i class="align-middle" data-feather="plus-square"></i>
                                <span class="align-middle">Add New BIN</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item {{ request()->is('order_categories*') ? 'active' : '' }} ">
                    <a data-target="#categories" data-toggle="collapse" class="sidebar-link collapsed">
                        <i class="align-middle" data-feather="grid"></i>
                        <span class="align-middle">Categories</span>
                    </a>
                    <ul id="categories"
                        class="sidebar-dropdown list-unstyled collapse {{ request()->is('order_categories*') ? 'show' : '' }}"
                        data-parent="#sidebar">

                        <li class="sidebar-item {{ request()->is('order_categories') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('order_categories.index') }}">
                                <i class="align-middle" data-feather="grid"></i>
                                <span class="align-middle">All Categories</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('order_categories/create') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('order_categories.create') }}">
                                <i class="align-middle" data-feather="plus-square"></i>
                                <span class="align-middle">Add New Category</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item {{ request()->is('reports*') ? 'active' : '' }} ">
                    <a data-target="#report" data-toggle="collapse" class="sidebar-link collapsed">
                        <i class="align-middle" data-feather="file-text"></i>
                        <span class="align-middle">Reports</span>
                    </a>
                    <ul id="report"
                        class="sidebar-dropdown list-unstyled collapse {{ request()->is('bins*') ? 'show' : '' }}"
                        data-parent="#sidebar">

                        <li class="sidebar-item {{ request()->is('reports/payable') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('report.payable') }}">
                                <i class="align-middle" data-feather="dollar-sign"></i>
                                <span class="align-middle">Payable</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('reports/payable') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('report.daily') }}">
                                <i class="align-middle" data-feather="dollar-sign"></i>
                                <span class="align-middle">Daily</span>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="sidebar-item {{ request()->is('bins*') ? 'active' : '' }} ">
                    <a data-target="#gateways" data-toggle="collapse" class="sidebar-link collapsed">
                        <i class="align-middle" data-feather="server"></i>
                        <span class="align-middle">Gateways</span>
                    </a>
                    <ul id="gateways"
                        class="sidebar-dropdown list-unstyled collapse {{ request()->is('gateways*') ? 'show' : '' }}"
                        data-parent="#sidebar">

                        <li class="sidebar-item {{ request()->is('gateways') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('gateways.index') }}">
                                <i class="align-middle" data-feather="server"></i>
                                <span class="align-middle">All Gateways</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('gateways/create') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('gateways.create') }}">
                                <i class="align-middle" data-feather="plus-square"></i>
                                <span class="align-middle">Add New Gateway</span>
                            </a>
                        </li>
                    </ul>
                </li>

            @endif

                            <li class="sidebar-item {{ request()->is('feedbacks*') ? 'active' : '' }} ">
                    <a data-target="#feedback" data-toggle="collapse" class="sidebar-link collapsed">
                        <i class="align-middle" data-feather="flag"></i>
                        <span class="align-middle">Feedbacks</span>
                    </a>
                    <ul id="feedback"
                        class="sidebar-dropdown list-unstyled collapse {{ request()->is('paxful*') ? 'show' : '' }}"
                        data-parent="#sidebar">
                        <li class="sidebar-item {{ request()->is('feedbacks') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('feedbacks.index') }}">
                                <i class="align-middle" data-feather="flag"></i>
                                <span class="align-middle">All Feedbacks</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('paxful/trades/all') ? 'active' : '' }}">
                            <a class="sidebar-link" href="/dashboard">
                                <i class="align-middle" data-feather="plus-circle"></i>
                                <span class="align-middle">Add New Feedback</span>
                            </a>
                        </li>

                    </ul>
                </li>

              @if( in_array($role, ['admin', 'customer']) )
                <li class="sidebar-header">
                    Manage Website
                </li>
                <li class="sidebar-item {{ request()->is('settings') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('settings.index') }}">
                        <i class="align-middle" data-feather="sliders"></i>
                        <span class="align-middle">Availability</span>
                    </a>
                </li>
            @endif

            @if( $role == 'manager')
            <li class="sidebar-item {{ request()->is('tags*') ? 'active' : '' }} ">
                <a data-target="#tags" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="message-circle"></i>
                    <span class="align-middle">Tags</span>
                </a>
                <ul id="tags"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->is('tags*') ? 'show' : '' }}"
                    data-parent="#sidebar">

                    <li class="sidebar-item {{ request()->is('tags') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('tags.index') }}">
                            <i class="align-middle" data-feather="message-circle"></i>
                            <span class="align-middle">All Tags</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->is('tags/create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('tags.create') }}">
                            <i class="align-middle" data-feather="plus-square"></i>
                            <span class="align-middle">Add New Tag</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
{{--            <li class="sidebar-item {{ request()->is('messages*') ? 'active' : '' }} ">--}}
{{--                <a data-target="#messages" data-toggle="collapse" class="sidebar-link collapsed">--}}
{{--                    <i class="align-middle" data-feather="message-circle"></i>--}}
{{--                    <span class="align-middle">Messages</span>--}}
{{--                </a>--}}
{{--                <ul id="messages"--}}
{{--                    class="sidebar-dropdown list-unstyled collapse {{ request()->is('messages*') ? 'show' : '' }}"--}}
{{--                    data-parent="#sidebar">--}}

{{--                    <li class="sidebar-item {{ request()->is('messages') ? 'active' : '' }}">--}}
{{--                        <a class="sidebar-link" href="{{ route('messages.index') }}">--}}
{{--                            <i class="align-middle" data-feather="message-circle"></i>--}}
{{--                            <span class="align-middle">All Messages</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="sidebar-item {{ request()->is('messages/create') ? 'active' : '' }}">--}}
{{--                        <a class="sidebar-link" href="{{ route('messages.create') }}">--}}
{{--                            <i class="align-middle" data-feather="plus-square"></i>--}}
{{--                            <span class="align-middle">Add New Message</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </li>--}}


        </ul>
    </div>
</nav>
