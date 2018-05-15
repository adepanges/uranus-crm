            <!-- Left navbar-header -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav">
                    <div class="sidebar-head">
                        <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">Navigation</span></h3> </div>
                    <ul class="nav" id="side-menu">
@if($access_list->management_cs_team)
                        <li>
                            <a href="{{ site_url('statement') }}">
                                <i class="fa fa-money"></i> <span class="hide-menu">Account Statement</span>
                            </a>
                        </li>
@endif
                    </ul>
                </div>
            </div>
            <!-- Left navbar-header end -->
