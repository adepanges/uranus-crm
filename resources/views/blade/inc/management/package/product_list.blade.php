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
        <script src="{{ base_url('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
        <script src="{{ base_url('js/validator.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/select2-4.0.6-rc.1/dist/js/select2.min.js') }}"></script>

        <script type="text/javascript">
            package_ = {
                product_package_id: {{ $package->product_package_id }}
            }
        </script>
        <script src="{{ base_url('js/module/management/package_product_list.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.management_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Package - Product List</h4>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 pull-right">
                    <a href="{{ site_url('package') }}" class="btn btn-success form-control">Kembali</a>
                </div>
                <!-- /.page title -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" role="form">
                        <div class="col-md-6 white-box">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="state-success">Code & Name</label>
                                <div class="col-md-6">
                                    <span class="form-control">[{{ $package->code }}] {{ $package->name }}</span>
                                </div>
                            </div>
@if($package->status==1)
                            <div class="form-group has-success">
                                <label class="col-md-3 control-label" for="state-success">Status</label>
                                <div class="col-md-6">
                                    <span class="form-control">activated</span>
                                </div>
                            </div>
@else
                            <div class="form-group has-error">
                                <label class="col-md-3 control-label" for="state-success">Status</label>
                                <div class="col-md-6">
                                    <span class="form-control">deactivated</span>
                                </div>
                            </div>
@endif
                        </div>


                        <div class="col-md-6 white-box">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="state-success">Tipe Harga</label>
                                <div class="col-md-6">
                                    <span class="form-control">{{ $package->price_type }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="state-success">Total Harga</label>
                                <div class="col-md-6">
                                    <span class="form-control">{{ rupiah($package->price) }}</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">
                        <table id="productListTable" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Merk</th>
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>
                                        Action
                                        @if($access_list->management_network_postback_add)
                                            <button onclick="addProductList()" style="margin-left: 4px;" type="button" class="btn btn-success btn-circle btn-sm m-r-5"><i class="ti-plus"></i></button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- .row -->

            <div class="modal fade" id="addProductListModal" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Add Product List</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-9">
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
                                <div class="col-md-3">
                                    aa
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

            <div class="modal fade" id="productListModal" role="dialog" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Update Product List Info</h4>
                        </div>
                        <div class="modal-body">
                            <form id="productListForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="product_package_id">
                                <input type="hidden" name="product_package_list_id">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Name</label>
                                    <input type="text" class="form-control" name="name" data-error="Hmm, name produt harap diisi" required>
                                    <div class="help-block with-errors"></div>
                                </div>
@if($package->price_type == 'RETAIL')
                                <div class="form-group" id="fieldPrice">
                                    <label for="recipient-name" class="control-label">Harga Eceran  Paket</label>
                                    <div class="input-group m-b-30">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="number" class="form-control" name="price" data-error="Hmm, harga produt harap diisi" required>
                                        <span class="input-group-addon">.00</span>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>
@endif
                                <div class="form-group">
                                    <label for="message-text" class="control-label" style="margin-right: 10px;">Active</label>
                                    <input type="checkbox" name="status" value="1" checked class="js-switch" data-color="#99d683">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSaveProductList" type="button" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
