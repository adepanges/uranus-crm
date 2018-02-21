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



        <script src="{{ base_url('js/module/sso/role.js') }}" type="text/javascript"></script>
        <script type="text/javascript">
            user_role = {
                user_id: {{ $user->user_id }}
            }
        </script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.sso_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">User Role</h4>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 pull-right">
                    <a href="{{ site_url('user') }}" class="btn btn-success form-control">Kembali</a>
                </div>
                <!-- /.page title -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" role="form">
                        <div class="col-md-6 white-box">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="state-success">Username</label>
                                <div class="col-md-6">
                                    <span class="form-control">{{ $user->username }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="state-success">Email</label>
                                <div class="col-md-6">
                                    <span class="form-control">{{ $user->email }}</span>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 white-box">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="state-success">Nama Lengkap</label>
                                <div class="col-md-6">
                                    <span class="form-control">{{ "{$user->first_name} {$user->last_name}" }}</span>
                                </div>
                            </div>
@if($user->status==1)
                            <div class="form-group has-success">
                                <label class="col-md-3 control-label" for="state-success">Status</label>
                                <div class="col-md-6">
                                    <span class="form-control">activated</span>
                                </div>
                            </div>
@else
                            <div class="form-group has-error">
                                <label class="col-md-3 control-label" for="state-success">Status</label>
                                <div class="col-md-6">
                                    <span class="form-control">deactivated</span>
                                </div>
                            </div>
@endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">
                        <table id="RoleTable" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Role Name</th>
                                    <th>Franchise</th>
                                    <th>Created At</th>
                                    <th>
                                        Action
                                        @if($access_list->sso_users_role_add)
                                            <button onclick="addRole()" style="margin-left: 4px;" type="button" class="btn btn-success btn-circle btn-sm m-r-5"><i class="ti-plus"></i></button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- .row -->

            <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Role</h4>
                        </div>
                        <div class="modal-body">
                            <form id="roleForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="user_id">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Role</label>
                                    <select class="form-control" name="role_id" data-error="Hmm, role harap dipilih" required>
                                        <option value="" selected</option>
@foreach ($active_role as $key => $value)
                                        <option value="{{ $value->role_id }}">{{ $value->label }}</option>
@endforeach
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Franchise</label>
                                    <select class="form-control" name="franchise_id">
                                        <option value="1" selected>Dermeva Kosmetik Indonesia</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSaveRoleModal" type="button" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
