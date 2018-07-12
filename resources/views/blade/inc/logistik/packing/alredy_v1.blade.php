@extends('layout.default')

@section('title', $title)

@section('load_css')
@parent
        <link href="{{ base_url('plugins/bower_components/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/datatables-bootstrap/Buttons-1.5.1/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ base_url('plugins/bower_components/switchery/dist/switchery.min.css') }}" rel="stylesheet" />
        <link href="{{ base_url('plugins/bower_components/select2-4.0.6-rc.1/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ base_url('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
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
        <script src="{{ base_url('plugins/bower_components/select2-4.0.6-rc.1/dist/js/select2.min.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ base_url('js/validator.js') }}"></script>
        <script src="{{ base_url('js/module/logistik/packing_alredy_v1.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.logistik_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Pesanan sudah dipacking</h4> </div>
                <!-- /.page title -->
            </div>

            <div class="row white-box" id="filterSection">
                <div class="col-md-1">
                    <b>Sale Date</b>
                </div>
                <div class="col-md-3">
                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control" name="start" value="{{ date('Y-m-d') }}">
                        <span class="input-group-addon bg-info b-0 text-white">to</span>
                        <input type="text" class="form-control" name="end" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                @if(isset($list_cs) && !empty($list_cs))
                <div class="col-md-2">
                    <select class="form-control" name="filter_cs_id">
                        <option value="0">All</option>
                        @foreach ($list_cs as $key => $value)
                        <option value="{{ $value->user_id }}">{{ $value->first_name.' '.$value->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <input type="hidden" name="filter_cs_id" value="0">
                @endif

                <div class="col-md-2 pull-right">
                    <button onclick="pickUpBulk()" class="btn btn-info btn-rounded form-control">
                        <i class="mdi mdi-package-up"></i>
                        <span>Sudah Pickup</span>
                    </button>
                </div>

                <div class="col-md-2 pull-right">
                    <button class="btn btn-rounded form-control" onclick="ordersTable.ajax.reload()">
                        <i class="fa fa-search"></i>
                        <span>Filter</span>
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">
                        <table id="ordersTable" class="table">
                            <thead>
                                <tr>
                                    <th rowspan="2"><input id="logistics_checklist_bulk" type="checkbox"></th>
                                    <th rowspan="2">No</th>
                                    <th colspan="2" style="text-align: center;">Tanggal</th>
                                    <th rowspan="2">Order ID</th>
                                    <th rowspan="2">Nama Customer</th>
                                    <th rowspan="2">Product Package</th>
                                    <th rowspan="2">Info</th>
                                    <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                    <th style="width: 80px;">Orders</th>
                                    <th style="width: 80px;">Sale</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
@endsection
