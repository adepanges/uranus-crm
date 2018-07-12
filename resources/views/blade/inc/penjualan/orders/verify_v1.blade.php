@extends('layout.default')

@section('title', $title)

@section('load_css')
@parent
        <link href="{{ base_url('plugins/bower_components/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/datatables-bootstrap/Buttons-1.5.1/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ base_url('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
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
        <script src="{{ base_url('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ base_url('js/module/penjualan/orders_verify_v1.js') }}" type="text/javascript"></script>
        <script src="{{ base_url('js/module/penjualan/orders_badge_v1.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.penjualan_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Verify Payment Orders</h4>
                </div>
            </div>

            <div class="row white-box" id="filterSection">
                <div class="col-md-2">
                    <b>Action Date</b>
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
                                    <th rowspan="2">No</th>
                                    <th colspan="2" style="text-align: center;">Tanggal</th>
                                    <th rowspan="2">Order ID</th>
                                    <th rowspan="2">Customer Name</th>
                                    <th rowspan="2">Contact</th>
                                    <th rowspan="2">Product Package</th>
                                    <th rowspan="2">Total Price</th>
                                    <th rowspan="2">Info</th>
                                    <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                    <th style="width: 80px;">Orders</th>
                                    <th style="width: 80px;">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="saleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Sale Information</h4>
                        </div>
                        <div class="modal-body">
                            <form id="saleForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="order_id">
                                <div class="form-group">
                                    <label class="control-label">Payment Method</label>
                                    <select class="form-control" name="payment_method_id">
@foreach ($master_payment_method as $key => $value)
                                        <option value="{{ $value->payment_method_id }}">
                                            {{ $value->name }}
                                        </option>
@endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Tanggal Bayar</label>
                                    <input type="text" class="form-control" name="paid_date" id="datepicker-autoclose" placeholder="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSaveSaleModal" type="button" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
