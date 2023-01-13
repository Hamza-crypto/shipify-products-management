
<nav id="sidebar" class="sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('product.index') }}">
            <span class="align-middle me-3">{{ env("APP_NAME") }}</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                General
            </li>
            <li class="sidebar-item {{ request()->is('products') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('product.index') }}">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle">All Products</span>
                </a>
            </li>

            <li class="sidebar-header">
                Manage Products
            </li>

            <li class="sidebar-item {{ request()->is('products/sku') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('product.sku') }}">
                    <i class="align-middle" data-feather="plus-square"></i>
                    <span class="align-middle">Upload CSV</span>
                </a>
            </li>

        </ul>
    </div>
</nav>
