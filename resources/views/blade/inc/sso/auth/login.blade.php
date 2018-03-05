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
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>
                            </div>
                        </div>
                    </form>
@endsection
