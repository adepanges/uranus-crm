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
        <script src="{{ base_url('js/module/penjualan/orders_double_detail_v1.js') }}" type="text/javascript"></script>
        <script src="{{ base_url('js/module/penjualan/orders_badge_v1.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.penjualan_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-md-12">
                    <h1>Orders Code: </h1>
                </div>
                <!-- /.page title -->
            </div>

            <div class="row white-box">
                    <div class="col-md-2 pull-right">
                        <button onclick="window.location = '{{ site_url($orders_state) }}'" class="btn btn-success btn-rounded form-control">
                            <i class="ti-arrow-left m-l-5"></i>
                            <span>Kembali</span>
                        </button>
                    </div>
@if($access_list->penjualan_orders_to_trash || $role_active->role_id == 6)
                    <div class="col-md-2 pull-right">
                        <button onclick="trashDoubleOrders({{ $orders_double->orders_double_id }})" class="btn btn-warning btn-rounded form-control">
                            <i class="icon-trash"></i>
                            <span>Buang</span>
                        </button>
                    </div>
@endif
            </div>

            <div class="col-md-12 white-box">
                <form class="form-horizontal" role="form">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="state-success">Customer</label>
                            <div class="col-md-6">
                                <span class="form-control">{{ $orders_double->customer_name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="state-success">Telephone</label>
                            <div class="col-md-6">
                                <span class="form-control">{{ $orders_double->customer_telephone }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="state-success">Reason</label>
                            <div class="col-md-6">
                                <span class="form-control">{{ $orders_double->double_reason }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="state-success">Created At</label>
                            <div class="col-md-6">
                                <span class="form-control">{{ $orders_double->created_at }}</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

@foreach ($orders as $key => $value)
            <div class="col-lg-3 col-md-4 col-xs-12 double_side">
                <div class="white-box">
                    <b>{{ $value->order_code }}</b><br>
                    {{ $value->created_at }}
                    <hr>
                    <span class="label label-info">{{ $value->order_status }}</span>
                    <span class="label label-warning">{{ $value->logistics_status }}</span><br>
                    Paket: {{ $value->package_name }}<br>
                    <span class="label label-success">{{ rupiah($value->total_price) }}</span>
                    <span class="label label-info"><i class="icon-wallet"></i> {{ $value->payment_method }}</span>
                    {{-- <hr>
                    Follow Up By: --}}
                    <hr>
                    <?php
                    $customer_info = json_decode($value->customer_info);
                    $customer_address = json_decode($value->customer_address);
                    ?>
                    Nama Pemesan: <b>{{ $customer_info->full_name }}</b><br>
                    Alamat Pemesan: <b>{{ "{$customer_address->address} Ds./Kel. {$customer_address->desa_kelurahan} Kec. {$customer_address->kecamatan} Kab./Kota. {$customer_address->kabupaten} Prov. {$customer_address->provinsi},  {$customer_address->postal_code}" }}</b><br>
                    Follow Up Method: <span class="label label-warning">{{ $value->call_method }}</span>
                    <hr>
@if($access_list->penjualan_orders_double_pulihkan)
                    <a onclick="pulihkanOrders({{ $value->order_id }})" class="btn btn-rounded btn-success pull-right">Pulihkan</a>&nbsp;
@endif
@if($access_list->penjualan_orders_to_trash || $role_active->role_id == 6)
                    <a onclick="trashOrders({{ $value->order_id }})" class="btn btn-rounded btn-warning pull-right"><i class="icon-trash"></i></a>
@endif
                    <br>
                </div>
            </div>
@endforeach

            <script type="text/javascript">
                document.app.penjualan.orders = {!! json_encode($orders) !!};
            </script>
@endsection
