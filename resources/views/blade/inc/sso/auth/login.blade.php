@extends('layout.auth')

@section('title', $title)

@section('content')
                    <form class="form-horizontal form-material" method="POST" action="{{ site_url('auth/log/validate/web') }}">
                        <input type="hidden" name="auth_access_key" value="{{ $auth_access_key }}">
                        <a href="javascript:void(0)" class="text-center db">
                            <img src="{{ base_url('images/logo/dermeva_logo_205x41.png') }}" alt="Home" />
                        </a>

                        @if (isset($error_message) && !empty($error_message))
                            <div class="alert alert-danger"> {{ $error_message }} </div>
                        @endif

                        <div class="form-group m-t-40">
                            <div class="col-xs-12">
                                <input name="username" class="form-control" type="text" required="" placeholder="Username / Email" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input name="password" class="form-control" type="password" required="" placeholder="Password">
                            </div>
                        </div>
                        <!--
                        <div class="form-group">
                            <div class="col-md-12">
                            <div class="checkbox checkbox-primary pull-left p-t-0">
                                <input id="checkbox-signup" type="checkbox">
                                <label for="checkbox-signup"> Remember me </label>
                            </div>
                            <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a> </div>
                        </div>
                        -->
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>
                            </div>
                        </div>
                        <!--
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                                <div class="social"><a href="javascript:void(0)" class="btn  btn-facebook" data-toggle="tooltip"  title="Login with Facebook"> <i aria-hidden="true" class="fa fa-facebook"></i> </a> <a href="javascript:void(0)" class="btn btn-googleplus" data-toggle="tooltip"  title="Login with Google"> <i aria-hidden="true" class="fa fa-google-plus"></i> </a> </div>
                            </div>
                        </div>
                        <div class="form-group m-b-0">
                            <div class="col-sm-12 text-center">
                                <p>Don't have an account? <a href="register2.html" class="text-primary m-l-5"><b>Sign Up</b></a></p>
                            </div>
                        </div>
                    -->
                    </form>
                    <!--
                    <form class="form-horizontal" id="recoverform" action="index.html">
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <h3>Recover Password</h3>
                            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                        </div>
                    </div>
                    </form>
                    -->
@endsection
