        <!-- Left navbar-header -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav slimscrollsidebar">
                <div class="sidebar-head">
                    <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">Navigation</span></h3> </div>
                <ul class="nav" id="side-menu">
                    <li class="user-pro">
                        <a href="#" class="waves-effect"><img src="{{ base_url('images/users/7.jpg') }}" alt="user-img" class="img-circle">
                            <span class="hide-menu"> {{ "{$profile['first_name']} {$profile['last_name']}  ({$profile['username']})" }}<span class="fa arrow"></span></span>
                        </a>
                        <ul class="nav nav-second-level collapse" aria-expanded="false" style="height: 0px;">
                            <li><a href="javascript:void(0)"><i class="ti-user"></i> <span class="hide-menu">My Profile</span></a></li>
                            <li><a href="{{ base_url('sso.php/auth/log/out') }}"><i class="fa fa-power-off"></i> <span class="hide-menu">Logout</span></a></li>
                        </ul>
                    </li>

                    <!--
                    <li>
                        <a href="javascript:void(0)" class="waves-effect active">
                            <i data-icon="7" class="linea-icon linea-basic fa-fw"></i>
                            <span class="hide-menu">Link type
                                <span class="label label-rouded label-purple pull-right">2</span>
                            </span>
                        </a>
                    </li>
                    -->
                </ul>
            </div>
        </div>
        <!-- Left navbar-header end -->
