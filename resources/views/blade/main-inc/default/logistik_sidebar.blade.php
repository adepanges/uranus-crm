            <!-- Left navbar-header -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav">
                    <div class="sidebar-head">
                        <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">Navigation</span></h3>
                    </div>
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="javascript:void(0)" class="waves-effect">
                                <i class="mdi mdi-package"></i>
                                <span class="hide-menu">Packing <span class="fa fa-caret-down"></span></span>
                            </a>
                            <ul class="nav nav-second-level">
@if($access_list->logistik_packing_notyet)
                                <li>
                                    <a href="{{ site_url('packing_v1/app') }}" >
                                        <i class="mdi mdi-package-variant"></i>
                                        <span class="hide-menu">Belum Packing</span>
                                    </a>
                                </li>
@endif
@if($access_list->logistik_packing_alredy)
                                <li>
                                    <a href="{{ site_url('packing_v1/alredy_pack') }}" >
                                        <i class="mdi mdi-package-variant-closed"></i>
                                        <span class="hide-menu">Sudah Packing</span>
                                    </a>
                                </li>
@endif
@if($access_list->logistik_packing_pickup)
                                <li>
                                    <a href="{{ site_url('packing_v1/pickup') }}" >
                                        <i class="mdi mdi-package-up"></i>
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
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Left navbar-header end -->


            <script type="text/javascript">
                document.app.logistics = {
                    packing_state: '{{ $packing_state }}'
                }
            </script>
