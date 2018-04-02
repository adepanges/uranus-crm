@extends('layout.default')

@section('title', $title)

@section('load_css')
@parent
        <link href="{{ base_url('plugins/bower_components/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/datatables-bootstrap/Buttons-1.5.1/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ base_url('plugins/bower_components/switchery/dist/switchery.min.css') }}" rel="stylesheet" />
@endsection

@section('load_js')
@parent
        <script src="{{ base_url('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/datatables-bootstrap/Buttons-1.5.1/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/datatables-bootstrap/Buttons-1.5.1/js/buttons.flash.min.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/blockUI/jquery.blockUI.js') }}"></script>
        <!-- Sweet-Alert  -->
        <script src="{{ base_url('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/sweetalert/jquery.sweet-alert.custom.js')}}"></script>
        <script src="{{ base_url('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
        <script src="{{ base_url('js/validator.js') }}"></script>

        <script src="{{ base_url('js/module/sso/user.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.sso_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">User</h4> </div>
                <!-- /.page title -->
            </div>

            <!-- .row -->
            <div class="row">
                <div class="col-md-12 white-box" id="filterSection">
                    <div class="col-md-2 pull-right">
                        <button class="btn btn-info form-control" onclick="userTable.ajax.reload()">Filter</button>
                    </div>
                    <div class="col-md-2 pull-right">
                        <select name="role_id" class="form-control input-md">
                            <option value="0" selected>All</option>
@foreach ($active_role as $key => $value)
                            <option value="{{ $value->role_id }}">{{ $value->label }}</option>
@endforeach
                        </select>
                    </div>
                </div>
            </div>
            <!-- .row -->

            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">
                        <table id="UserTable" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Status</th>
                                    <th>
                                        Action
                                        @if($access_list->sso_users_add)
                                            <button onclick="addUser()" style="margin-left: 4px;" type="button" class="btn btn-success btn-circle btn-sm m-r-5"><i class="ti-plus"></i></button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- .row -->

            <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">User</h4> </div>
                        <div class="modal-body">
                            <form id="userForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="user_id">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Username</label>
                                    <input type="text" class="form-control" name="username" data-error="Hmm, Username harap diisi" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Password</label>
                                    <i style="font-size: 9px;">Abaikan jika tidak dirubah</i>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="password" name="password" data-toggle="validator" data-minlength="6" class="form-control" id="inputPassword" placeholder="Password" required> <span class="help-block">Minimal 6 Karakter</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="password" name="password" class="form-control" data-match="#inputPassword" data-match-error="Hmm, password tidak sama" placeholder="Confirm" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
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
                                <div class="form-group">
                                    <label for="message-text" class="control-label" style="margin-right: 10px;">Active</label>
                                    <input type="checkbox" name="status" value="1" checked class="js-switch" data-color="#99d683">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSaveUserModal" type="button" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
