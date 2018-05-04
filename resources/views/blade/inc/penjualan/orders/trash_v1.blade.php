@extends('layout.default')

@section('title', $title)

@section('load_css')
@parent
        <link href="{{ base_url('plugins/bower_components/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/datatables-bootstrap/Buttons-1.5.1/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
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
        <script src="{{ base_url('js/module/penjualan/orders_trash_v1.js') }}" type="text/javascript"></script>
        <script src="{{ base_url('js/module/penjualan/orders_badge_v1.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.penjualan_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Trash Orders</h4> </div>
                <!-- /.page title -->
            </div>

            <div class="row white-box">
                <div class="col-md-2 pull-right">
                    <button onclick="deleteBulk()" class="btn btn-danger btn-rounded form-control">
                        <i class="mdi mdi-package-variant-closed"></i>
                        <span>Hapus</span>
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">
                        <table id="ordersTable" class="table">
                            <thead>
                                <tr>
                                    <th><input id="logistics_checklist_bulk" type="checkbox"></th>
                                    <th>No</th>
                                    <th>Tanggal Order</th>
                                    <th>Order ID</th>
                                    <th>Customer Name</th>
                                    <th>Contact</th>
                                    <th>Product Package</th>
                                    <th>Total Price</th>
                                    <th>Info</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
@endsection
