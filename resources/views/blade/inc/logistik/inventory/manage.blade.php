@extends('layout.default')

@section('title', $title)

@section('load_css')
@parent
        <link href="{{ base_url('plugins/bower_components/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('load_js')
@parent
        <script src="{{ base_url('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/datatables-bootstrap/Buttons-1.5.1/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/blockUI/jquery.blockUI.js') }}"></script>
        <script src="{{ base_url('js/module/logistik/manage_product.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.logistik_sidebar')
@endsection

@section('content')
            <script type="text/javascript">
                document.app.product = {!! json_encode($product) !!};
            </script>

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-5 white-box">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Merk</label>
                                <div class="col-md-9">
                                    <span class="form-control">{{ $product->merk }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 white-box">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>
                                <div class="col-md-9">
                                    <span class="form-control">{{ $product->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 white-box">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <span class="form-control">{{ $product->code }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="white-box">
                                <h3 class="box-title">Stok</h3>
                                <div class="text-right">
                                    <h1>{{ $product->current_stock }}</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="white-box">
                                <h3 class="box-title">Barang Masuk</h3>
                                <div class="text-right">
                                    <h1><sup><i class="ti-arrow-up text-success"></i></sup> {{ $product->jumlah_masuk }}</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="white-box">
                                <h3 class="box-title">Barang Keluar</h3>
                                <div class="text-right">
                                    <h1><sup><i class="ti-arrow-down text-danger"></i></sup> {{ $product->jumlah_keluar }}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">
                        <table id="stockTable" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Jumlah</th>
                                    <th>Terpakai</th>
                                    <th>Catatan</th>
                                    <th></th>
                                    <th>
                                        <button class="btn btn-success btn-rounded" onclick="add()">Re-Stock</button>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- .row -->

            <div class="modal fade" id="formModal" role="dialog" aria-labelledby="exampleModalLabel1"
            style="z-index: 1041 !important;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">User</h4> </div>
                        <div class="modal-body">
                            <form id="dataForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="team_cs_id">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Nama Tim</label>
                                    <input type="text" class="form-control" name="name" data-error="Hmm, Username harap diisi" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Franchise</label>
                                    <select class="form-control" name="franchise_id">
                                        <option value="1" selected>Dermeva Kosmetik Indonesia</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Leader Tim</label>
                                    <select id="leaderSelect" class="form-control" name="leader_id">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="control-label" style="margin-right: 10px;">Active</label>
                                    <input type="checkbox" name="status" value="1" checked class="js-switch" data-color="#99d683">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSaveCsTeamModal" type="button" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

@endsection
