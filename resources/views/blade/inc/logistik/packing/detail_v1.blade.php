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
        <script src="{{ base_url('js/module/logistik/packing_detail_v1.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.logistik_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-md-12">
                    <h1>Orders Code: {{ $orders->order_code }}</h1>
                </div>
                <!-- /.page title -->
            </div>

            <div class="row white-box">

                <div class="col-md-2 pull-right">
                    <button onclick="window.location = '{{ site_url($packing_state) }}'" class="btn btn-success btn-rounded form-control">
                        <i class="ti-arrow-left m-l-5"></i>
                        <span>Kembali</span>
                    </button>
                </div>

                <div class="col-md-2 pull-right">
                    <a target="_blank" href="{{ site_url('packing_v1/cetak/label/'.base64_encode($orders->order_id)) }}" class="btn btn-primary btn-rounded form-control">
                        <i class="fa fa-print"></i>
                        <span>Label Pengiriman</span>
                    </a>
                </div>

@if($access_list->logistik_packing_action_alredy && $orders->logistics_status_id == 1)
                <div class="col-md-2 pull-right">
                    <button onclick="donePacking({{ $orders->order_id }})" class="btn btn-warning btn-rounded form-control">
                        <i class="mdi mdi-package-variant-closed"></i>
                        <span>Sudah Packing</span>
                    </button>
                </div>
@endif

@if($access_list->logistik_packing_action_pickup && $orders->logistics_status_id == 3)
                <div class="col-md-2 pull-right">
                    <button onclick="pickUp({{ $orders->order_id }})" class="btn btn-info btn-rounded form-control">
                        <i class="mdi mdi-package-up"></i>
                        <span>Sudah Pickup</span>
                    </button>
                </div>
@endif
@if($access_list->logistik_packing_action_shipping && $orders->logistics_status_id == 4)
                <div class="col-md-2 pull-right">
                    <button onclick="onShipping('{!! base64_encode(json_encode([
                            'order_id' => $orders->order_id,
                            'logistic_id' => $orders->logistic_id,
                            'shipping_code' => $orders->shipping_code
                        ])) !!}')" class="btn btn-success btn-rounded form-control">
                        <i class="fa fa-truck"></i>
                        <span>Pengiriman</span>
                    </button>
                </div>
@endif
            </div>

            <div class="row white-box">
                <div class="col-md-6 col-xs-12">
                    <div class="row">
                        <div class="col-sm-9">
                            <h1>Info Customer</h1>
                        </div>
                        <div class="col-sm-2">

@if(in_array($orders->order_status_id,[2,3,4,5]) && $access_list->penjualan_orders_update_customer_info)
                            <span class="circle circle-sm bg-danger di" onclick="updateCustomerInfo({{ $orders->order_id }})" style="cursor: pointer;">
                                <i class="ti-pencil-alt"></i>
                            </span>
@endif
                        </div>
                    </div>
                    <br>

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
                                <textarea rows="4" class="form-control input-sm" name="address" {{ $attr_readonly }}>{{ "{$orders->customer_address->address} Ds./Kel. {$orders->customer_address->desa_kelurahan} Kec. {$orders->customer_address->kecamatan} {$orders->customer_address->kabupaten} Prov. {$orders->customer_address->provinsi},  {$orders->customer_address->postal_code}" }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Logistik</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="logistic_id">
                                    <option>Pilih</option>
@foreach ($master_logistics as $key => $value)
                                    <option value="{{ $value->logistic_id }}" {{ ($orders->logistic_id==$value->logistic_id)?'selected':'' }}>{{ ucwords(strtolower($value->name)) }}</option>
@endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="row">
                        <div class="col-sm-9">
                            <h1>List Orders</h1>
                        </div>
                        <div class="col-sm-2">
@if(in_array($orders->order_status_id,[2,3,4,5,6]) && $access_list->penjualan_orders_update_shopping_info)
                            <span class="circle circle-sm bg-danger di" onclick="updateShoopingCart({{ $orders->order_id }})" style="cursor: pointer;">
                                <i class="ti-pencil-alt"></i>
                            </span>
@endif
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <form class="form-horizontal" >
@if(!empty($orders->order_invoice_id) && !empty($orders->invoice_number))
                            <div class="form-group">
                                <label class="control-label col-sm-3">Invoice</label>
                                <div class="col-sm-8">
                                    <span class="btn btn-info form-control input-sm" style="cursor: pointer;"><b>{{ $orders->invoice_number }}</b></span>
                                </div>
                            </div>
@endif
                            <div class="form-group">
                                <label class="control-label col-sm-3">Payment Method</label>
                                <div class="col-sm-8">
                                    <select class="form-control input-sm" name="payment_method" {{ $attr_readonly }}>
@foreach ($master_payment_method as $key => $value)
                                        <option value="{{ $value->payment_method_id }}" {{ ($value->payment_method_id == $orders->payment_method_id)?'selected':'' }}>{{ $value->name }}</option>
@endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
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

@foreach ($logistics_process as $key => $value)
                    <div class="row">
                        <div class="col-md-3">
                            <b>{{ $value->created_at }}</b>
                        </div>
                        <div class="col-md-9" style="border-bottom: 1px dotted #000;">
                            <h6><b>{{ $value->status }}</b> oleh {{ $value->full_name }}</h6>
                            {!! $value->notes !!}
                        </div>
                    </div>
@endforeach
                </div>
            </div>

            <div class="modal fade" id="shippingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Shipping Information</h4> </div>
                        <div class="modal-body">
                            <form id="shippingForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="order_id">
                                <div class="form-group">
                                    <label class="control-label">Shipping</label>
                                    <select class="form-control" name="logistic_id">
@foreach ($master_logistics as $key => $value)
                                        <option value="{{ $value->logistic_id }}">{{ $value->name }}</option>
@endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">No. Resi</label>
                                    <input type="text" class="form-control" name="shipping_code">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSaveShipInfoModal" type="button" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                document.app.penjualan.orders = {!! json_encode($orders) !!};
            </script>
@endsection
