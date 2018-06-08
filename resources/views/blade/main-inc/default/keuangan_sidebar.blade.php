            <!-- Left navbar-header -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav">
                    <div class="sidebar-head">
                        <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">Navigation</span></h3> </div>
                    <ul class="nav" id="side-menu">
@if($access_list->account_statement)
                        <li>
                            <a href="{{ site_url('statement') }}">
                                <i class="fa fa-money"></i> <span class="hide-menu">Account Statement</span>
                            </a>
                        </li>
@endif
                        <li> <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-bank"></i> <span class="hide-menu"> Back Statement <span class="fa arrow"></span> </span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{ site_url('bank_statement/bca') }}">
                                        <img src="{{ base_url('images/bank/icon-bca.png') }}" width="100">
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ site_url('bank_statement/mandiri') }}">
                                        <img src="{{ base_url('images/bank/icon-mandiri.png') }}" width="100">
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ site_url('bank_statement/bri') }}">
                                        <img src="{{ base_url('images/bank/icon-bri.png') }}" width="100">
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
            <!-- Left navbar-header end -->
