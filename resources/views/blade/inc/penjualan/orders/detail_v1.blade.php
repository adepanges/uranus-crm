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
        <script src="{{ base_url('js/module/penjualan/detail_v1.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.penjualan_sidebar')
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

@if($orders->order_status_id != 2 || $orders->is_deleted == 1)
                <div class="col-md-2 pull-right">
                    <button onclick="window.location = '{{ site_url($orders_state) }}'" class="btn btn-success btn-rounded form-control">
                        <i class="ti-arrow-left m-l-5"></i>
                        <span>Kembali</span>
                    </button>
                </div>
@endif

{{-- is deleted --}}
@if($orders->is_deleted == 0)

@if(in_array($orders->order_status_id, [2,3]))
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

{{-- is deleted --}}
@else
    <div class="col-md-2 pull-right">
        <button onclick="pulihkanTrashOrders({{ $orders->order_id }})" class="btn btn-warning btn-rounded form-control">
            <span>Pulihkan</span>
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

@if(
    (in_array($orders->order_status_id,[2,3,5]) && $access_list->penjualan_orders_update_customer_info) ||
    in_array($role_active->role_id, [1,2])
)
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
                                <textarea rows="4" class="form-control input-sm" name="address" {{ $attr_readonly }}>{{ "{$orders->customer_address->address} Ds./Kel. {$orders->customer_address->desa_kelurahan} Kec. {$orders->customer_address->kecamatan} Kab./Kota. {$orders->customer_address->kabupaten} Prov. {$orders->customer_address->provinsi},  {$orders->customer_address->postal_code}" }}</textarea>
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
@if(!empty($orders->shipping_code))
                        <div class="form-group">
                            <label class="control-label col-sm-3">Resi</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="shipping_code" value="{{ $orders->shipping_code }}" readonly>
                            </div>
                        </div>
@endif
                        <hr>
@if(!empty($orders->order_invoice_id))
    @if(!empty($orders->invoice_number))
                        <div class="form-group">
                            <label class="control-label col-sm-3">Invoice</label>
                            <div class="col-sm-8" onclick="window.open('{{ site_url('orders_v1/cetak/invoice/'.$orders->order_id) }}')">
                                <span class="btn btn-info form-control input-sm" style="cursor: pointer;"><b>{{ $orders->invoice_number }}</b></span>
                            </div>
                        </div>
    @endif
    @if(!empty($orders->paid_date))
                        <div class="form-group">
                            <label class="control-label col-sm-3">Transfer Date</label>
                            <div class="col-sm-8">
                                <span class="form-control input-sm" style="cursor: pointer;"><b>{{ $orders->paid_date }}</b></span>
                            </div>
                        </div>
    @endif
    @if(!empty($orders->invoice_total_price))
                        <div class="form-group">
                            <label class="control-label col-sm-3">Price on Invoice</label>
                            <div class="col-sm-8">
                                <span class="form-control input-sm" style="cursor: pointer;"><b>{{ rupiah($orders->invoice_total_price) }}</b></span>
                            </div>
                        </div>
                        <i>Jika informasi pada invoice tidak sesuai dengan yg sekarang silahkan untuk update invoice</i>
                        <br>
    @endif
@endif
@if(
    $orders->order_status_id > 6 && in_array($role_active->role_id, [1,2])
)
                            <div class="form-group">
                                <button class="btn btn-info pull-right" onclick="updInvoice(); return false;">Update Invoice</button>
                            </div>
@endif
                    </form>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="row">
                        <div class="col-sm-9">
                            <h1>List Orders</h1>
                        </div>

                        <div class="col-sm-2">
@if(
    (in_array($orders->order_status_id, [2,3,5,6]) && $access_list->penjualan_orders_update_shopping_info) ||
    in_array($role_active->role_id, [1,2])
)
                            <span class="circle circle-sm bg-danger di" onclick="updateShoopingCart({{ $orders->order_id }})" style="cursor: pointer;">
                                <i class="ti-pencil-alt"></i>
                            </span>
@endif
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <form class="form-horizontal" >
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
                        <div class="col-md-5">
                            <h3>{{ $value['info']->package_name }}</h3>
                        </div>
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-5">
    @if($value['info']->price_type == 'PACKAGE')
                            <h3>{{ rupiah($value['info']->package_price) }}</h3>
    @else
                            <h3>&nbsp;</h3>
    @endif
                        </div>
                        <br>
    @foreach ($value['cart'] as $key_cart => $value_cart)
        <?php
            $btn_del = '';
            if(
                !$value_cart->is_package &&
                (
                    (
                        !empty($value_cart->product_id) &&
                        in_array($orders->order_status_id, [2,3,5]) &&
                        $access_list->penjualan_orders_update_shopping_info
                    ) ||
                    (
                        $orders->order_status_id == 6 &&
                        $role_active->role_id == 3
                    ) ||
                    in_array($role_active->role_id, [1,2]
                )
            )
            ) $btn_del = '<span class="delete_cart" onclick="deleteCart('.$value_cart->cart_id.')">[ x ]</span>';;
        ?>

                        <div class="row" style="padding-left: 40px;">
                            <div class="col-md-5" style="border-bottom: 1px dotted #000;">
                                <h5>{!! $btn_del !!}{{ $value_cart->product_name }}</h5>
                            </div>
                            <div class="col-md-2" style="border-bottom: 1px dotted #000;">
        @if(!empty($value_cart->product_id))
                                <h5>Qty. {{ $value_cart->qty }}</h5>
        @else
                                <h5>&nbsp;</h5>
        @endif
                            </div>
                            <div class="col-md-5">
        @if($value['info']->price_type == 'RETAIL')
                                    <h3>{{ rupiah($value_cart->price) }}</h3>
        @else
                                    <h3></h3>
        @endif
                            </div>
                        </div>
    @endforeach
                    </div>
@endforeach
                    <br>
                    <div class="row" style="margin-top: 7px;">
                        <div class="col-md-5" style="border-bottom: 1px dotted #000;"><h2>Total</h2></div>
                        <div class="col-md-2" style="border-bottom: 1px dotted #000;"><h2>&nbsp</h2></div>
                        <div class="col-md-5"><h2>{{ rupiah($orders->total_price) }}</h2></div>
                    </div>
                    <div class="row">
@if(
    (
        $orders->order_status_id == 6 &&
        $role_active->role_id == 3
    ) ||
    in_array($role_active->role_id, [1,2])
)
                        <div class="pull-right">
                            <button class="btn btn-info" onclick="addonShoopingCart()">Tambahkan Biaya</button>
                        </div>
@endif
@if(
    (in_array($orders->order_status_id, [2,3,5]) && $access_list->penjualan_orders_update_shopping_info) ||
    in_array($role_active->role_id, [1,2])
)
                        <div class="pull-right" style="margin-right: 5px;">
                            <button class="btn btn-info" onclick="addProductList()">Tambah Produk</button>
                        </div>
@endif
                    </div>
                </div>
            </div>

            <div class="row white-box">
                <div class="col-md-6 col-xs-12">
                    <h1>History Orders</h1>

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

            <div class="modal fade" id="addProductListModal" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Add Product List</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12" style="border-right: 1px solid #000;">
                                    <div class="white-box">
                                        <table id="productTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Harga</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnAddProductList" type="button" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

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

            <div class="modal fade" id="updateCustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Update Customer Info</h4> </div>
                        <div class="modal-body">
                            <form id="updateCustomerInfoForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="order_id">
                                <input type="hidden" name="customer_id">
                                <input type="hidden" name="customer_address_id">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Orders Code</label>
                                    <input type="text" class="form-control" name="order_code" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Full Name</label>
                                    <input type="text" class="form-control" name="full_name">
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Telephone</label>
                                    <input type="text" class="form-control" name="telephone">
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Metode Follow Up</label>
                                    <select class="form-control" name="call_method_id">
@foreach ($master_call_method as $key => $value)
                                        <option value="{{ $value->call_method_id }}">{{ $value->name }}</option>
@endforeach
                                    </select>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Alamat Pengiriman:</label>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Alamat</label>
                                    <textarea class="form-control" name="address"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Provinsi</label>
                                    <input type="hidden" name="provinsi" value="">
                                    <input type="hidden" name="provinsi_id" value="">
                                    <select class="form-control" id="provinsi_id_select">
                                        <option>Pilih</option>
@foreach ($master_wilayah_provinsi as $key => $value)
                                        <option value="{{ $value->id }}">{{ ucwords(strtolower($value->name)) }}</option>
@endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Kabupaten</label>
                                    <input type="hidden" name="kabupaten" value="">
                                    <input type="hidden" name="kabupaten_id" value="">
                                    <select class="form-control" id="kabupaten_id_select">
                                        <option>Pilih</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Kecamatan</label>
                                    <input type="hidden" name="kecamatan" value="">
                                    <input type="hidden" name="kecamatan_id" value="">
                                    <select class="form-control" id="kecamatan_id_select">
                                        <option>Pilih</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Desa / Kelurahan</label>
                                    <input type="hidden" name="desa_kelurahan" value="">
                                    <input type="hidden" name="desa_id" value="">
                                    <select class="form-control" id="desa_id_select">
                                        <option>Pilih</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Kode Pos</label>
                                    <input type="text" class="form-control" name="postal_code">
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Logistik</label>
                                    <select class="form-control" name="logistic_id">
                                        <option>Pilih</option>
@foreach ($master_logistics as $key => $value)
                                        <option value="{{ $value->logistic_id }}">{{ ucwords(strtolower($value->name)) }}</option>
@endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSaveCustomerModal" type="button" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="shopingCartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Update Shooping Info</h4> </div>
                        <div class="modal-body">
                            <form id="shopingCartForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="order_id" value="{{ $orders->order_id }}">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Payment Method</label>
                                    <select class="form-control input-sm" name="payment_method_id">
                                        <option>Pilih</option>
@foreach ($master_payment_method as $key => $value)
                                        <option
                                            value="{{ $value->payment_method_id }}"
                                            {{ ($value->payment_method_id == $orders->payment_method_id)?'selected':'' }}
                                            >
                                            {{ $value->name }}
                                        </option>
@endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Product Package</label>
                                    <select class="form-control input-sm" name="product_package_id">
    @foreach ($master_product_package as $key => $value)
                                        <option data="{{ base64_encode(json_encode($value)) }}" value="{{ $value->product_package_id }}" {{ ($value->product_package_id == $orders_cart_package_id)?'selected':'' }}>{{ $value->name }}</option>
    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Qty</label>
                                    <input type="number" class="form-control input-sm" name="qty" value="1">
                                </div>
                                <hr>
                                <div class="form-group row" id="detailCart">

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSaveShopingCartModal" type="button" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="pendingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Update</h4> </div>
                        <div class="modal-body">
                            <form id="pendingForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="order_id">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Alasan Pending</label>
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

            <div class="modal fade" id="saleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="exampleModalLabel1">Sale Information</h4>
                        </div>
                        <div class="modal-body">
                            <form id="saleForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="order_id" value="{{ $orders->order_id }}">
                                <div class="form-group">
                                    <label class="control-label">Payment Method</label>
                                    <select class="form-control" name="payment_method_id">
@foreach ($master_payment_method as $key => $value)
                                        <option
                                            value="{{ $value->payment_method_id }}"
                                            {{ ($value->payment_method_id == $orders->payment_method_id)?'selected':'' }}
                                            >
                                            {{ $value->name }}
                                        </option>
@endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Transfer Date</label>
                                    <input type="text" class="form-control" name="paid_date" id="datepicker-autoclose1" placeholder="yyyy-mm-dd" value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Nomor Invoice</label>
                                    <div class="input-group m-b-30">
                                        <input type="hidden" name="invoice_first" value="DKI/{{ date('Ymd') }}/">
                                        <span class="input-group-addon" id="basic-addon1">DKI/{{ date('Ymd') }}/</span>
                                        <input type="text" class="form-control" name="invoice_number" placeholder="000001" aria-describedby="basic-addon1"  data-error="Hmm, nomor invoice harap diisi" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
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

            <div class="modal fade" id="updInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="exampleModalLabel1">Invoice Information</h4>
                        </div>
                        <div class="modal-body">
                            <form id="updInvoiceForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="order_id" value="{{ $orders->order_id }}">
                                <input type="hidden" name="order_invoice_id" value="{{ $orders->order_invoice_id }}">
                                <div class="form-group">
                                    <label class="control-label">Transfer Date</label>
                                    <input type="text" class="form-control" name="paid_date" id="datepicker-autoclose2" placeholder="yyyy-mm-dd" value="{{ $orders->paid_date }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nomor Invoice</label>
                                    <input type="text" class="form-control" name="invoice_number" value="{{ $orders->invoice_number }}">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSaveUpdInvoiceModal" type="button" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addonShoopingCartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Tambahkan Biaya</h4>
                        </div>
                        <div class="modal-body">
                            <form id="addonShoopingCartForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="order_id" value="{{ $orders->order_id }}">
                                <div class="form-group">
                                    <label class="control-label">Name</label>
                                    <select name="name" class="form-control">
                                        <option value="Diskon">Diskon</option>
                                        <option value="Kode Unik">Kode Unik</option>
                                        <option value="">Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group"id="otherName" style="display: none;">
                                    <label class="control-label">Lainya</label>
                                    <input type="text" class="form-control" name="name_other">
                                </div>
                                <div class="form-group" id="fieldPrice">
                                    <label for="recipient-name" class="control-label">Harga</label>
                                    <div class="input-group m-b-30">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="number" class="form-control" name="price" data-error="Hmm, harga produt harap diisi" required>
                                        <span class="input-group-addon">.00</span>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSaveaddonShoopingCartModal" type="button" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                document.app.penjualan.orders = {!! json_encode($orders) !!};
            </script>
@endsection
