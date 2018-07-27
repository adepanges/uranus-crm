            <!-- Top Navigation -->
            <nav class="navbar navbar-default navbar-static-top m-b-0">
                <div class="navbar-header">
                    <!-- Toggle icon for mobile view -->
                    <div class="top-left-part">
                        <!-- Logo -->
                        <a class="logo" href="{{ base_url() }}">
                            <span class="hidden-xs">
                                <!--This is dark logo text-->
                                <img src="{{ $franchise_logo }}" alt="home" class="dark-logo" width="205px"/>
                                <!--This is light logo text-->
                                <img src="{{ $franchise_logo }}" alt="home" class="light-logo" width="205px"/>
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
                                            <a class="link-to-profile" href="{{ base_url('sso.php/user/profile/ref/'.base64_encode(current_url())) }}">
                                                <h4>{{ "{$profile['first_name']} {$profile['last_name']}" }}</h4>
                                                <p class="text-muted">{{ $profile['email'] }}</p>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="dw-user-box">
                                        <div class="u-text">
                                            <p class="text-muted">Switch Role</p>
                                            @foreach ($role as $key => $value)
                                                <p class="role_option">{{ $value['franchise_name'] }}</p>
                                                    @foreach ($value['role'] as $key => $value_role)
                                                        <a class="role_option_detail btn" onclick="window.location = '{{ base_url('sso.php/auth/init/index/switch_role/'.$value_role['chipper_user_role_id']) }}'">{{ $value_role['role_label'] }}</a>
                                                    @endforeach
                                                <hr>
                                            @endforeach
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
