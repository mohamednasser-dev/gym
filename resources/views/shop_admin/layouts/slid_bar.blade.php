<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">
    <div class="overlay"></div>
    <div class="search-overlay"></div>
    <!--  BEGIN SIDEBAR  -->
    <div class="sidebar-wrapper sidebar-theme">
        <nav id="sidebar">
            <div class="shadow-bottom"></div>
            <ul class="list-unstyled menu-categories" id="accordionExample">
                <li class="menu hole">
                    <a href="{{route('shop.home')}}" class="dropdown-toggle first-link">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            <span>{{ __('messages.home') }}</span>
                        </div>
                    </a>
                </li>
                <li class="menu product_type">
                    <a href="#product_type" data-active="true" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle first-link">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                            <span>{{ __('messages.product_types') }}</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled show" id="product_type" data-parent="#accordionExample">
                            <li class="active add">
                                <a href="{{ route('product_type.add') }}"> {{ __('messages.add') }} </a>
                            </li>
                        <li class="show" >
                            <a href="{{ route('product_type.index') }}"> {{ __('messages.show') }} </a>
                        </li>
                    </ul>
                </li>
                <li class="menu products">
                    <a href="#products" data-active="true" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle first-link">
                        <div class="">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                            <span>{{ __('messages.products') }}</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled show" id="products" data-parent="#accordionExample">
                        <li class="active add">
                            <a href="{{ route('products.search') }}"> {{ __('messages.add') }} </a>
                        </li>
                        <li class="show" >
                            <a href="{{ route('products.index') }}"> {{ __('messages.show') }} </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
    <!--  END SIDEBAR  -->
    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
