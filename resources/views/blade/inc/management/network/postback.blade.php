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
            network = {
                network_id: {{ $network->network_id }}
            }
        </script>
        <script src="{{ base_url('js/module/management/network_postback.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.management_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Postback - Network</h4>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 pull-right">
                    <a href="{{ site_url('network') }}" class="btn btn-success form-control">Kembali</a>
                </div>
                <!-- /.page title -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" role="form">
                        <div class="col-md-6 white-box">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="state-success">Network</label>
                                <div class="col-md-6">
                                    <span class="form-control">{{ $network->name }}</span>
                                </div>
                            </div>

                            <?php
                            $param_catch = explode(",", $network->catch);
                            foreach ($param_catch as $key => $value) {
                                $param_catch[$key] = "<code class='catch'>@".$value."</code>";
                            }
                            ?>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="state-success">Parameter Catch</label>
                                <div class="col-md-6">
                                    <p> {!! implode(" ", $param_catch) !!} </p>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 white-box">
@if($network->status==1)
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
                        <table id="postbackTable" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Link</th>
                                    <th>Event Name</th>
                                    <th>Trigger Orders</th>
                                    <th>Status</th>
                                    <th>
                                        Action
                                        @if($access_list->management_network_postback_add)
                                            <button onclick="addPostback()" style="margin-left: 4px;" type="button" class="btn btn-success btn-circle btn-sm m-r-5"><i class="ti-plus"></i></button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- .row -->

            <div class="modal fade" id="postbackModal" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Role</h4>
                        </div>
                        <div class="modal-body">
                            <form id="postbackForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="network_postback_id">
                                <input type="hidden" name="network_id">
                                <div class="form-group">
                                    <label class="control-label">Event</label>
                                    <select class="form-control" name="event_id" data-error="Hmm, Event harap dipilih" required>
                                        <option value="">Pilih</option>
@foreach ($event as $key => $value)
                                        <option value="{{ $value->event_id }}" data="{{ $value->trigger }}">{{ $value->name }}</option>
@endforeach
                                    </select>
                                    <div class="help-block with-errors"></div>
                                    Trigger : <code id="event-trigger">-</code>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Link</label>
                                    <input type="text" class="form-control" name="link" data-error="Hmm, link network harap diisi" required>
                                    <div class="help-block with-errors"></div>
                                    Available Paramater : {!! implode(" ", $param_catch) !!}
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="control-label" style="margin-right: 10px;">Active</label>
                                    <input type="checkbox" name="status" value="1" checked class="js-switch" data-color="#99d683">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSavePostbackModal" type="button" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
