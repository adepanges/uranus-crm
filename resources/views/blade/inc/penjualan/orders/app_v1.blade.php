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
        <script src="{{ base_url('js/module/penjualan/orders_v1.js') }}" type="text/javascript"></script>
        <script src="{{ base_url('js/module/penjualan/orders_badge_v1.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.penjualan_sidebar')
@endsection

@section('content')
            <script type="text/javascript">
                document.app.conf_assigned_to_cs = {{ (int) $conf_assigned_to_cs }};
            </script>

            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Orders New</h4> </div>
                <!-- /.page title -->
            </div>

            <div class="row white-box">
                <div class="col-md-2 pull-right">
                    <button onclick="ordersTable.ajax.reload()" class="btn btn-primary btn-rounded form-control">
                        <i class="fa fa-refresh"></i>
                        <span>Refresh</span>
                    </button>
                </div>
                <div class="col-md-2 pull-right">
                    <button onclick="assignOrders()" class="btn btn-rounded form-control">
                        <i class="fa fa-child"></i>
                        <span>Assign Orders</span>
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
                                    <th>Alamat</th>
                                    <th>Product Package</th>
                                    <th>Total Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="assignOrdersModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="exampleModalLabel1">Assign Orders</h4> </div>
                        <div class="modal-body">
                            <h4>
                                List CS
                                <button onclick="findCS()" style="margin-left: 4px;" type="button" class="btn btn-success btn-circle btn-sm m-r-5"><i class="ti-plus"></i></button>
                            </h4>
                            <div class="row">
                                <div id="list_cs" class="col-md-11 container-fluid">

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="radio" name="type_assign" value="selected" checked> Selected<br><br>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Jumlah</label>
                                        <div class="col-sm-8">
                                            <b id="totalSelected"></b> Orders
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" name="type_assign" value="bulk"> Bulk<br><br>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Jumlah</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" name="total_orders" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnProsesAssignModal" type="button" class="btn btn-primary">Proses</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="findCSModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="exampleModalLabel1">Find CS</h4> </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-3">
                                    Filter Tim CS
                                </div>
                                <div class="col-md-5">
                                    <select id="filter_team_cs" class="form-control">
                                        @foreach ($cs_team as $key => $value)
                                            <option value="{{ $value->team_cs_id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button onclick="listCSTable.ajax.reload()" class="btn btn-warning btn-rounded form-control">
                                        <i class="fa fa-filter"></i>
                                        <span>Filter</span>
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <table id="listCSTable" class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Lengkap</th>
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
