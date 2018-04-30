@extends('layout.default')

@section('title', $title)

@section('load_css')
@parent
        <link href="{{ base_url('plugins/bower_components/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/datatables-bootstrap/Buttons-1.5.1/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('load_js')
@parent
        <script src="{{ base_url('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/datatables-bootstrap/Buttons-1.5.1/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/datatables-bootstrap/Buttons-1.5.1/js/buttons.flash.min.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/blockUI/jquery.blockUI.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
        <!-- Sweet-Alert  -->
        <script src="{{ base_url('js/module/report/simple.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.report_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Report Simple</h4> </div>
                <!-- /.page title -->
            </div>

            <div class="row white-box" id="filterSection">
                <div class="col-md-12">
                    <input type="radio" name="by_date" id="choiceOrders" value="orders" checked> <label for="choiceOrders">Orders Date</label>
                    <input type="radio" name="by_date" id="choiceAction" value="action"> <label for="choiceAction">Action Date</label>
                </div>
                <br>
                <div class="col-md-4">
                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control" name="start" value="{{ date('Y-m-01') }}">
                        <span class="input-group-addon bg-info b-0 text-white">to</span>
                        <input type="text" class="form-control" name="end" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-rounded form-control" onclick="reportTable.ajax.reload()">
                        <i class="fa fa-search"></i>
                        <span>Filter</span>
                    </button>
                </div>
            </div>

            <div class="row white-box" id="informationSection">
                <div class="col-md-4">
                    <h3>Total Penjualan: <span style="color: #090;" id="fieldPenjualan">Rp. 0,-</span></h3>
                </div>
                <div class="col-md-4">
                    <h3>Total Sales: <span style="color: #090;" id="fieldSales">0</span></h3>
                </div>
                <div class="col-md-4">
                    <h3>Total Product: <span style="color: #090;" id="fieldProduct">0</span></h3>
                </div>
            </div>

            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">
                        <table id="reportTable" class="table">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">No</th>
                                    <th rowspan="2" style="vertical-align: middle;">Nama</th>
                                    <th rowspan="2" style="vertical-align: middle;">Penjualan</th>
                                    <th rowspan="2" style="vertical-align: middle;">Produk<br>Qty</th>
                                    <th colspan="6" style="text-align: center;">Total</th>
                                    <th colspan="2" style="text-align: center;">Rate</th>
                                </tr>
                                <tr>

                                    <th>Follow Up</th>
                                    <th>Pending</th>
                                    <th>Cancel</th>
                                    <th>Confirm Buy</th>
                                    <th>Verify Pay</th>
                                    <th>Sale</th>
                                    <th>Sales</th>
                                    <th>Active<br>Order</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- .row -->
@endsection
