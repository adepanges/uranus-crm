@extends('layout.default')

@section('title', $title)

@section('load_css')
@parent
        <link href="{{ base_url('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('load_js')
@parent
        <script src="{{ base_url('plugins/bower_components/blockUI/jquery.blockUI.js') }}"></script>
        <!-- Sweet-Alert  -->
        <script src="{{ base_url('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
        <script src="{{ base_url('js/validator.js') }}"></script>
        <script src="{{ base_url('js/module/sso/profile.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.profile_sidebar')
@endsection

@section('content')
            <div class="row white-box">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h2 class="page-title">Profile</h2>
                </div>

                @if(!empty($ref_link))
                <div class="pull-right col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <a href="{{ $ref_link }}" class="btn btn-warning form-control">Kembali</a>
                </div>
                @endif
            </div>


            <script type="text/javascript">
                document.app.user_profile = {!! json_encode($data_user) !!};
            </script>

            <div class="row">
                <div class="col-md-6 col-md-offset-3 white-box" id="filterSection">
                    <form id="userForm" data-toggle="validator" data-delay="100">
                        <input type="hidden" name="user_id">
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Username</label>
                            <input type="text" class="form-control" name="username" data-error="Hmm, Username harap diisi" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="control-label">Email</label>
                            <input type="email" class="form-control" name="email" data-error="Hmm, Email harap diisi dan valid" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Nama depan</label>
                            <input type="text" class="form-control" name="first_name" data-error="Hmm, nama depan harap diisi" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Nama belakang</label>
                            <input type="text" class="form-control" name="last_name">
                        </div>
                        <hr>
                        <i style="color: #f00;">Abaikan jika password tidak ingin dirubah</i>
                        <br>
                        <br>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Password Lama</label>
                            <input type="password" class="form-control" name="password_old" data-error="Hmm, Password Lama harap diisi">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Password Baru</label>
                            <i style="font-size: 9px;">Abaikan jika tidak dirubah</i>
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="password" name="password_new" data-toggle="validator" data-minlength="6" class="form-control" id="inputPassword" placeholder="New Password"> <span class="help-block">Minimal 6 Karakter</span>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" name="password_confirm" class="form-control" data-match="#inputPassword" data-match-error="Hmm, password tidak sama" placeholder="Confirm New Password">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="btnSaveUserForm" type="button" class="btn btn-info form-control">Simpan</button>
                            </div>
                        </row>
                    </form>
                </div>
            </div>
        </div>
@endsection
