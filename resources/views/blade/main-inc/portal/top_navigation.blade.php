            <!-- Top Navigation -->
            <nav class="navbar navbar-default navbar-static-top m-b-0">
                <div class="navbar-header">
                    <!-- Toggle icon for mobile view -->
                    <div class="top-left-part">
                        <!-- Logo -->
                        <a class="logo" href="{{ base_url() }}">
                            <span class="hidden-xs">
                                <!--This is dark logo text-->
                                <img src="{{ base_url('images/logo/dermeva_logo_205x41.png') }}" alt="home" class="dark-logo" />
                                <!--This is light logo text-->
                                <img src="{{ base_url('images/logo/dermeva_logo_205x41.png') }}" alt="home" class="light-logo" />
                             </span>
                         </a>
                    </div>
                    <!-- /Logo -->
                    <ul class="nav navbar-top-links navbar-left">
                        <li class="mega-dropdown open">
                            <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                                <span class="hidden-xs">{{ $role_active->franchise_name }}</span>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav navbar-top-links navbar-right pull-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#">
                                <b class="hidden-xs">{{ $role_active->role_label }}</b>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated flipInX">
                                <li>
                                    <div class="dw-user-box">
                                        <div class="u-text">
                                            <h4>{{ "{$profile['first_name']} {$profile['last_name']}" }}</h4>
                                            <p class="text-muted">{{ $profile['email'] }}</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="dw-user-box">
                                        <div class="u-text">
                                            <p class="text-muted">Switch Role</p>
                                        </div>
                                    </div>
                                </li>
                                <li><a href="{{ $logout_link }}"><i class="fa fa-power-off"></i> Logout</a></li>
                            </ul>
                            <!-- /.dropdown-user -->
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Top Navigation -->
