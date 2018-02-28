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
                                <span class="hide-menu">Orders New</span>
                                <span class="badge badge-danger" id="count_new_order">?</span>
                            </a>
                        </li>
@endif
@if($access_list->penjualan_orders_confirm_buy)
                        <li>
                            <a href="{{ site_url('orders_v1/confirm_buy') }}">
                                <i class="fa fa-shopping-cart"></i>
                                <span class="hide-menu">Orders Confirm Buy</span>
                                <span class="badge badge-info" id="count_confirm_buy">?</span>
                            </a>
                        </li>
@endif
@if($access_list->penjualan_orders_pending)
                        <li>
                            <a href="{{ site_url('orders_v1/pending') }}">
                                <i class="mdi mdi-briefcase-download" style="font-size: 13px;"></i>
                                <span class="hide-menu">Orders Pending</span>
                                <span class="badge badge-warning" id="count_pending">?</span>
                            </a>
                        </li>
@endif
@if($access_list->penjualan_orders_cancel)
                        <li>
                            <a href="{{ site_url('orders_v1/cancel') }}">
                                <i class="mdi mdi-cart-off" style="font-size: 13px;"></i>
                                <span class="hide-menu">Orders Cancel</span>
                                <span class="badge badge-info" id="count_cancel">?</span>
                            </a>
                        </li>
@endif
                    </ul>
                </div>
            </div>
            <!-- Left navbar-header end -->


            <script type="text/javascript">
                document.app.penjualan = {
                    orders_state: '{{ $orders_state }}'
                }
            </script>
