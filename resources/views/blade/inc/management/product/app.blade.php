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
        <script src="{{ base_url('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
        <script src="{{ base_url('js/validator.js') }}"></script>
        <script src="{{ base_url('js/module/management/product.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.management_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Management Product</h4> </div>
                <!-- /.page title -->
            </div>

            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">
                        <table id="productTable" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Code</th>
                                    <th>Merk</th>
                                    <th>Name</th>
                                    <th>Berat</th>
                                    <th>Harga<br>Retail</th>
                                    <th>Status</th>
                                    <th>
                                        Action
                                        @if($access_list->management_product_add)
                                            <button onclick="addProduct()" style="margin-left: 4px;" type="button" class="btn btn-success btn-circle btn-sm m-r-5"><i class="ti-plus"></i></button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- .row -->

            <div class="modal fade" id="productModal" role="dialog" aria-labelledby="exampleModalLabel1"
            style="z-index: 1041 !important;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Network</h4> </div>
                        <div class="modal-body">
                            <form id="productForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="product_id">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Code</label>
                                    <input type="text" class="form-control" name="code" data-error="Hmm, code produt harap diisi" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Merk</label>
                                    <input type="text" class="form-control" name="merk" data-error="Hmm, merk produt harap diisi" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Name</label>
                                    <input type="text" class="form-control" name="name" data-error="Hmm, name produt harap diisi" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Berat</label>
                                    <input type="number" class="form-control" name="weight" data-error="Hmm, berat produt harap diisi" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Harga</label>
                                    <div class="input-group m-b-30">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="number" class="form-control" name="price" data-error="Hmm, harga produt harap diisi" required>
                                        <span class="input-group-addon">.00</span>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="control-label" style="margin-right: 10px;">Active</label>
                                    <input type="checkbox" name="status" value="1" checked class="js-switch" data-color="#99d683">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSaveProduct" type="button" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
