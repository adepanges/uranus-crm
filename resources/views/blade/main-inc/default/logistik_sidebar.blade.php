            <!-- Left navbar-header -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav">
                    <div class="sidebar-head">
                        <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">Navigation</span></h3>
                    </div>
                    <ul class="nav" id="side-menu">
@if($access_list->logistik_packing_notyet)
                        <li>
                            <a href="{{ site_url('packing_v1/app') }}" >
                                <i class="mdi mdi-package-variant"style="font-size: 13px;"></i>
                                <span class="hide-menu">Belum Packing</span>
                            </a>
                        </li>
@endif
@if($access_list->logistik_packing_alredy)
                        <li>
                            <a href="{{ site_url('packing_v1/alredy_pack') }}" >
                                <i class="mdi mdi-package-variant-closed"style="font-size: 13px;"></i>
                                <span class="hide-menu">Sudah Packing</span>
                            </a>
                        </li>
@endif
@if($access_list->logistik_packing_pickup)
                        <li>
                            <a href="{{ site_url('packing_v1/pickup') }}" >
                                <i class="mdi mdi-package-up"style="font-size: 13px;"></i>
                                <span class="hide-menu">Sudah Pickup</span>
                            </a>
                        </li>
@endif
@if($access_list->logistik_orders_shipping)
                        <li>
                            <a href="{{ site_url('packing_v1/shipping') }}" >
                                <i class="fa fa-truck"></i>
                                <span class="hide-menu">Pengiriman</span>
                            </a>
                        </li>
@endif
                    </ul>
                </div>
            </div>
            <!-- Left navbar-header end -->


            <script type="text/javascript">
                document.app.logistics = {
                    packing_state: '{{ $packing_state }}'
                }
            </script>
