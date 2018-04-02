            <!-- Left navbar-header -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav">
                    <div class="sidebar-head">
                        <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">Navigation</span></h3> </div>
                    <ul class="nav" id="side-menu">
@if($access_list->management_cs_team)
                        <li>
                            <a href="{{ site_url('cs_team') }}">
                                <i class="mdi mdi-account-switch" style="font-size: 13px;"></i>
                                <span class="hide-menu">CS Team</span>
                            </a>
                        </li>
@endif
@if($access_list->management_network)
                        <li>
                            <a href="{{ site_url('network') }}">
                                <i class="fa fa-exchange"></i>
                                <span class="hide-menu">Network</span>
                            </a>
                        </li>
@endif
@if($access_list->management_cs_team)
                        <li>
                            <a href="{{ site_url('product') }}">
                                <i class="fa-fw">P</i>
                                <span class="hide-menu">Product</span>
                            </a>
                        </li>
@endif
                        <li>
                            <a href="{{ site_url('package') }}">
                                <i class="fa-fw">PK</i>
                                <span class="hide-menu">Package</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Left navbar-header end -->
