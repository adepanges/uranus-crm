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
        <script src="{{ base_url('js/module/penjualan/detail_v1.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.penjualan_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Detail Pesanan</h4>
                </div>
                <!-- /.page title -->
            </div>

            <div class="row white-box">

@if($orders->order_status_id != 2)
                <div class="col-md-2 pull-right">
                    <button onclick="window.location = '{{ site_url($orders_state) }}'" class="btn btn-success btn-rounded form-control">
                        <i class="ti-arrow-left m-l-5"></i>
                        <span>Kembali</span>
                    </button>
                </div>
@else
    @if($access_list->penjualan_orders_action_pending)
                <div class="col-md-2 pull-right">
                    <button onclick="pendingOrders({{ $orders->order_id }})" class="btn btn-primary btn-rounded form-control">
                        <i class="mdi mdi-briefcase-download"></i>
                        <span>Pending</span>
                    </button>
                </div>
    @endif
    @if($access_list->penjualan_orders_action_cancel)
                <div class="col-md-2 pull-right">
                    <button onclick="cancelOrders({{ $orders->order_id }})" class="btn btn-danger btn-rounded form-control">
                        <i class="mdi mdi-cart-off"></i>
                        <span>Cancel</span>
                    </button>
                </div>
    @endif
    @if($access_list->penjualan_orders_action_confirm_buy)
                <div class="col-md-2 pull-right">
                    <button onclick="confirmBuy({{ $orders->order_id }})" class="btn btn-success btn-rounded form-control">
                        <i class="mdi mdi-cart-outline"></i>
                        <span>Confirm Buy</span>
                    </button>
                </div>
    @endif
@endif

@if ($orders->order_status_id == 5 && $access_list->penjualan_orders_action_verify_payment)
                <div class="col-md-2 pull-right">
                    <button onclick="verifyPayment({{ $orders->order_id }})" class="btn btn-warning btn-rounded form-control">
                        <i class="fa fa-credit-card"></i>
                        <span>Verify Payment</span>
                    </button>
                </div>
@endif

@if ($orders->order_status_id == 6 && $access_list->penjualan_orders_action_sale)
                <div class="col-md-2 pull-right">
                    <button onclick="saleOrders({{ $orders->order_id }})" class="btn btn-warning btn-rounded form-control">
                        <i class="fa fa-money"></i>
                        <span>Sale</span>
                    </button>
                </div>
@endif


@if($orders->order_status_id == 1 && $access_list->penjualan_orders_action_follow_up)
                <div class="col-md-2 pull-right">
                    <button onclick="followUp({{ $orders->order_id }})" class="btn btn-primary btn-rounded form-control">
                        <i class="mdi mdi-briefcase-upload"></i>
                        <span>Follow Up</span>
                    </button>
                </div>
@endif

            </div>

            <div class="row white-box">
                <div class="col-md-6 col-xs-12">
                    <h1>Info Customer</h1>

                    <form id="ordersForm" class="form-horizontal" data-toggle="validator" data-delay="100">
                        <div class="form-group">
                            <label class="control-label col-sm-3">Full Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control input-sm" name="full_name" value="{{ $orders->customer_info->full_name }}" {{ $attr_readonly }}>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Telephone</label>
                            <div class="col-sm-1"><i class="{{ $orders->call_method_icon }}"></i></div>
                            <div class="col-sm-7">
                                <input value="{{ $orders->customer_info->telephone }}" type="text" class="form-control input-sm" name="telephone" {{ $attr_readonly }}>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Alamat</label>
                            <div class="col-sm-8">
                                <textarea rows="4" class="form-control input-sm" name="address" {{ $attr_readonly }}>{{ "{$orders->customer_address->address} Ds./Kel. {$orders->customer_address->desa_kelurahan} Kec. {$orders->customer_address->kecamatan} Kab. {$orders->customer_address->kabupaten} Prov. {$orders->customer_address->provinsi},  {$orders->customer_address->postal_code}" }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Payment Method</label>
                            <div class="col-sm-8">
                                <select class="form-control input-sm" name="payment_method" {{ $attr_readonly }}>
@foreach ($master_payment_method as $key => $value)
                                    <option value="{{ $value->payment_method_id }}">{{ $value->name }}</option>
@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Orders Code</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control input-sm" name="full_name" value="{{ $orders->order_code }}" {{ $attr_readonly }}>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 col-xs-12">
                    <h1>List Orders</h1>
                    <br>
@foreach ($orders_cart_package as $key => $value)
                    <div class="row" style="margin-top: 7px;">
                        <div class="col-md-8">
                            <h3>{{ $value['info']->package_name }}</h3>
                        </div>
                        <div class="col-md-4">
@if($value['info']->price_type = 'PACKAGE')
                            <h3>{{ rupiah($value['info']->package_price) }}</h3>
@endif
                        </div>
@foreach ($value['cart'] as $key_cart => $value_cart)
                        <div class="row" style="padding-left: 40px;">
                            <div class="col-md-6" style="border-bottom: 1px dotted #000;">
                                <h5>{{ $value_cart->product_name }}</h5>
                            </div>
                            <div class="col-md-3" style="border-bottom: 1px dotted #000;">
                                <h5>Qty. {{ $value_cart->qty }}</h5>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>
@endforeach
                    </div>
@endforeach
                </div>
            </div>

            <div class="row white-box">
                <div class="col-md-6 col-xs-12">
                    <h1>History Status</h1>

@foreach ($orders_process as $key => $value)
                    <div class="row">
                        <div class="col-md-3">
                            <b>{{ $value->created_at }}</b>
                        </div>
                        <div class="col-md-9" style="border-bottom: 1px dotted #000;">
                            <h6>Status <b>{{ $value->status }}</b></h6>
                            {!! $value->notes !!}
                        </div>
                    </div>
@endforeach
                </div>
                <div class="col-md-6 col-xs-12">
                    <h1>History Logistik</h1>
                </div>
            </div>
@endsection

            <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Alasan dibatalkan</h4> </div>
                        <div class="modal-body">
                            <form id="cancelForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="order_id">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Alasan</label>
                                    <select class="form-control" name="notes">
@foreach ($reason_cancel as $key => $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
@endforeach
                                        <option value="dll">Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group" id="notes_etc">
                                    <label for="recipient-name" class="control-label">Alasan Lainya</label>
                                    <textarea class="form-control" name="notes_value"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSaveCancelModal" type="button" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="pendingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Alasan dipending</h4> </div>
                        <div class="modal-body">
                            <form id="pendingForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="order_id">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Alasan</label>
                                    <select class="form-control" name="notes">
@foreach ($reason_pending as $key => $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
@endforeach
                                        <option value="dll">Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group" id="pending_notes_etc">
                                    <label for="recipient-name" class="control-label">Alasan Lainya</label>
                                    <textarea class="form-control" name="notes_value"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSavePendingModal" type="button" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </div>
