            <!-- Left navbar-header -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav">
                    <div class="sidebar-head">
                        <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">Navigation</span></h3> </div>
                    <ul class="nav" id="side-menu">
@if($access_list->penjualan_orders_new)
                        <li>
                            <a href="{{ site_url('orders_v1/app') }}">
                                <i class="ti-shopping-cart"></i>
                                <span class="hide-menu">New Orders</span>
                                <span class="badge badge-danger" id="count_new_order">?</span>
                            </a>
                        </li>
@endif
@if($access_list->penjualan_orders_double)
                        <li>
                            <a href="{{ site_url('orders_v1/double') }}">
                                <i class="mdi mdi-briefcase-download" style="font-size: 13px;"></i>
                                <span class="hide-menu">Double Orders</span>
                                <span class="badge badge-warning" id="count_double">?</span>
                            </a>
                        </li>
@endif
@if($access_list->penjualan_orders_pending)
                        <li>
                            <a href="{{ site_url('orders_v1/pending') }}">
                                <i class="mdi mdi-briefcase-download" style="font-size: 13px;"></i>
                                <span class="hide-menu">Pending Orders</span>
                                <span class="badge badge-warning" id="count_pending">?</span>
                            </a>
                        </li>
@endif
@if($access_list->penjualan_orders_confirm_buy)
                        <li>
                            <a href="{{ site_url('orders_v1/confirm_buy') }}">
                                <i class="fa fa-shopping-cart"></i>
                                <span class="hide-menu">Confirm Buy Orders</span>
                                <span class="badge badge-info" id="count_confirm_buy">?</span>
                            </a>
                        </li>
@endif
@if($access_list->penjualan_orders_verify_payment)
                        <li>
                            <a href="{{ site_url('orders_v1/verify') }}">
                                <i class="fa fa-shopping-cart"></i>
                                <span class="hide-menu">Verify Payment Orders</span>
                                <span class="badge badge-info" id="count_verify_pay">?</span>
                            </a>
                        </li>
@endif
@if($access_list->penjualan_orders_sale)
                        <li>
                            <a href="{{ site_url('orders_v1/sale') }}">
                                <i class="fa fa-money" style="font-size: 13px;"></i>
                                <span class="hide-menu">Sale Orders</span>
                                <span class="badge badge-info" id="count_sale">?</span>
                            </a>
                        </li>
@endif

                        <li> <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-cart-outline fa-fw" data-icon="v" style="font-size: 13px;"></i> <span class="hide-menu"> Other <span class="fa arrow"></span> </span></a>
                        <ul class="nav nav-second-level">
                            @if($access_list->penjualan_orders_cancel)
                                <li>
                                    <a href="{{ site_url('orders_v1/cancel') }}">
                                        <i class="mdi mdi-cart-off" style="font-size: 13px;"></i>
                                        <span class="hide-menu">Cancel Orders</span>
                                        <span class="badge badge-info" id="count_cancel">?</span>
                                    </a>
                                </li>
                            @endif
                            @if($access_list->penjualan_orders_trash)
                                <li>
                                    <a href="{{ site_url('orders_v1/trash') }}">
                                        <i class="fa fa-trash" style="font-size: 13px;"></i>
                                        <span class="hide-menu">Trash Orders</span>
                                        <span class="badge badge-info" id="count_trash">?</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                        </li>

                    </ul>
                </div>
            </div>
            <!-- Left navbar-header end -->

            <script type="text/javascript">
                document.app.penjualan = {
                    orders_state: '{{ $orders_state }}',
                    interval_badge_load: 10000
                }
            </script>
