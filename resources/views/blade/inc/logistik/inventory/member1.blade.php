@extends('layout.default')

@section('title', $title)

@section('load_css')
@parent
        <link href="{{ base_url('plugins/bower_components/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/datatables-bootstrap/Buttons-1.5.1/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ base_url('plugins/bower_components/switchery/dist/switchery.min.css') }}" rel="stylesheet" />
        <link href="{{ base_url('plugins/bower_components/select2-4.0.6-rc.1/dist/css/select2.min.css') }}" rel="stylesheet" />
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
        <script src="{{ base_url('plugins/bower_components/select2-4.0.6-rc.1/dist/js/select2.min.js') }}"></script>

        <script type="text/javascript">
            cs_team = {
                team_cs_id: {{ $cs_team->team_cs_id }}
            }
        </script>
        <script src="{{ base_url('js/module/management/cs_team_member.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.management_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Management CS Team - Member</h4>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 pull-right">
                    <a href="{{ site_url('cs_team') }}" class="btn btn-success form-control">Kembali</a>
                </div>
                <!-- /.page title -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" role="form">
                        <input type="hidden" id="fieldTeamCsId" value="{{ $cs_team->team_cs_id }}">
                        <div class="col-md-6 white-box">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="state-success">Nama Tim</label>
                                <div class="col-md-6">
                                    <span class="form-control">{{ $cs_team->name }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="state-success">Franchise</label>
                                <div class="col-md-6">
                                    <span class="form-control">{{ $cs_team->franchise_name }}</span>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 white-box">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="state-success">Username Leader</label>
                                <div class="col-md-6">
                                    <span class="form-control">{{ $cs_team->username }}</span>
                                </div>
                            </div>
@if($cs_team->status==1)
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
                        <table id="MemberTable" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Member Name</th>
                                    <th>Email</th>
                                    <th>
                                        Action
                                        @if($access_list->management_cs_team_member_add)
                                            <button onclick="addMember()" style="margin-left: 4px;" type="button" class="btn btn-success btn-circle btn-sm m-r-5"><i class="ti-plus"></i></button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- .row -->

            <div class="modal fade" id="memberModal" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Custimer Service</h4>
                        </div>
                        <div class="modal-body">
                            <table id="lisCsTable" class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Member Name</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
