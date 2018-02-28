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
        <script src="{{ base_url('plugins/bower_components/select2-4.0.6-rc.1/dist/js/select2.min.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
        <script src="{{ base_url('js/validator.js') }}"></script>
        <script src="{{ base_url('js/module/penjualan/orders_pending_v1.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.penjualan_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Orders Pending</h4> </div>
                <!-- /.page title -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">
                        <table id="ordersTable" class="table">
                            <thead>
                                <tr>
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

            <div class="modal fade" id="ordersModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Detail Pesanan</h4> </div>
                        <div class="modal-body">
                            <form id="ordersForm" class="form-horizontal" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="user_id">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Full Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control input-sm" name="full_name" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Telephone</label>
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control input-sm" name="telephone" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Alamat</label>
                                            <div class="col-sm-8">
                                                <textarea rows="4" class="form-control input-sm" name="address" readonly></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Payment Method</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control input-sm" name="payment_method" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        List Orders
                                    </div>
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
