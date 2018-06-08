@extends('layout.default')

@section('title', $title)

@section('load_css')
@parent
        <link href="{{ base_url('plugins/bower_components/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/datatables-bootstrap/Buttons-1.5.1/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ base_url('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
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
        <script src="{{ base_url('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

        <script src="{{ base_url('js/validator.js') }}"></script>
        <script src="{{ base_url('js/module/keuangan/account_statement.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.keuangan_sidebar')
@endsection

@section('content')
            <div class="row white-box">
                <div class="col-sm-12 col-md-6">
                    <button class="col-md-12 btn btn-danger" onclick="addDebit()">
                        <h1 style="text-align: center;"><i class="fa fa-arrow-up"></i> DEBIT</h1>
                    </button>
                </div>
                <div class="col-sm-12 col-md-6">
                    <button class="col-md-12 btn btn-success" onclick="modalKredit()">
                        <h1 style="text-align: center;"><i class="fa fa-arrow-down"></i> KREDIT</h1>
                    </button>
                </div>
            </div>

            <div class="row white-box" id="filterSection">
                <div class="col-md-1">
                    <b>Trx Date</b>
                </div>
                <div class="col-md-3">
                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control" name="start" value="{{ date('Y-m-01') }}">
                        <span class="input-group-addon bg-info b-0 text-white">to</span>
                        <input type="text" class="form-control" name="end" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-rounded form-control" onclick="dataTable.ajax.reload()">
                        <i class="fa fa-search"></i>
                        <span>Filter</span>
                    </button>
                </div>
            </div>

            <div class="row white-box" id="actionSection">
                <div class="col-lg-3 col-md-4 col-sm-5 pull-right">
                    <button class="btn btn-danger btn-rounded form-control" onclick="commitInvoiceNumber()">
                        <span>Commit All Transaction</span>
                    </button>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-5 pull-right">
                    <button class="btn btn-warning btn-rounded form-control" onclick="sortInvoiceNumber()">
                        <span>Fix & Sort Invoice Number</span>
                    </button>
                </div>
            </div>

            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">
                        <table id="dataTable" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Account</th>
                                    <th>Invoice Number</th>
                                    <th>Trx Date</th>
                                    <th>Debit (-)</th>
                                    <th>Credit (+)</th>
                                    <th>Claim</th>
                                    <th>Commit</th>
                                    <th>
                                        Action
                                        @if($access_list->account_statement_add)
                                            <button onclick="add()" style="margin-left: 4px;" type="button" class="btn btn-success btn-circle btn-sm m-r-5"><i class="ti-plus"></i></button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>
            <!-- .row -->

            <div class="modal fade" id="opsiKreditModal" role="dialog" aria-labelledby="exampleModalLabel1"
            style="z-index: 1041 !important;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1"></h4>
                        </div>
                        <div class="modal-body" style="height: 120px;">
                            <div class="col-sm-12 col-md-6">
                                <button class="col-md-12 btn btn-success" onclick="addPenjualan()">
                                    <h1 style="text-align: center;">Penjualan</h1>
                                </button>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <button class="col-md-12 btn" onclick="addNonPenjualan()">
                                    <h1 style="text-align: center;">Non Penjualan</h1>
                                </button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="componentModal" role="dialog" aria-labelledby="exampleModalLabel1"
            style="z-index: 1041 !important;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Account Statement</h4>
                        </div>
                        <div class="modal-body">
                            <form id="appForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="account_statement_id">
                                <input type="hidden" name="is_sales">
                                <input type="hidden" name="transaction_type">

                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Account</label>
                                    <select class="form-control" name="payment_method_id" data-error="Hmm, sumber dana harap diisi" required>
                                        <option value="">Pilih</option>
                                    @foreach ($account as $key => $value)
                                        <option value="{{ $value->payment_method_id }}">{{ $value->name }}</option>
                                    @endforeach
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Trx Date</label>
                                    <input type="text" class="form-control" name="transaction_date" id="datepicker-autoclose1" placeholder="yyyy-mm-dd" value="{{ date('Y-m-d') }}" data-error="Hmm, tanggal transaksi harap diisi" autocomplete="off" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Trx Amount</label>
                                    <div class="input-group m-b-30">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="number" class="form-control" name="transaction_amount" data-error="Hmm, jumlah uang harap diisi" required>
                                        <span class="input-group-addon">.00</span>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Note</label>
                                    <textarea class="form-control" name="note"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Parent Trx</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input autocomplete="off" type="text" class="form-control" id="find_id_inv" placeholder="Masukan id atau nomor invoice">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-info form-control" onclick="findParentTrx()">Find</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="parent_trx">
                                </div>

                                <br>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSaveApp" type="button" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
