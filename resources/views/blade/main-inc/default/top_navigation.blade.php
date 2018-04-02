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

                <!-- This is the message dropdown -->
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <!-- /.Task dropdown -->
                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#">
                            <img src="{{ base_url('images/users/7.jpg') }}" alt="user-img" width="36" class="img-circle">
                            <b class="hidden-xs">{{ $profile['first_name'] }}</b>
                            <span style="font-size: 10px;">as {{ $role_active->role_label }}</span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-user animated flipInX">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img"><img src="{{ base_url('images/users/7.jpg') }}" alt="user" /></div>
                                    <div class="u-text">
                                        <h4>{{ "{$profile['first_name']} {$profile['last_name']}" }}</h4>
                                        <p class="text-muted">{{ $profile['email'] }}</p>
                                    </div>
                                </div>
                            </li>
                            <li><a href="{{ $logout_link }}"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>

                    <!-- /.dropdown -->
                </ul>

            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        <!-- End Top Navigation -->
