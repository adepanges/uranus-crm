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
        <script src="{{ base_url('js/module/keuangan/import_proccess.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.keuangan_sidebar')
@endsection

@section('content')

            <div class="row white-box">
                <div class="col-md-2">
                    <h4>Bank</h4>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="payment_method_id" data-error="Hmm, sumber dana harap diisi" required>
                        <option value="">Pilih</option>
                        @foreach ($account as $key => $value)
                            <option value="{{ $value->payment_method_id }}" {{ ($payment_method_id==$value->payment_method_id)?'selected':'' }}>{{ $value->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="col-md-12 btn btn-warning" onclick="prosesTransaction()">Proses</button>
                </div>
                <div class="col-md-3">
                    <button class="col-md-12 btn btn-success" onclick="document.location.reload()">Selesai</button>
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
                                    <th>Invoice Number</th>
                                    <th>Trx Date</th>
                                    <th>Debit (-)</th>
                                    <th>Credit (+)</th>
                                    <th style="width: 200px;">Note</th>
                                    <th style="text-align: center;">Processed</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($data_import as $key => $value)
                                <?php
                                    $cr = '';
                                    $db = '';
                                    $inv = '<code>will generated</code>';

                                    if($value['transaction_type'] == 'K') $cr = rupiah($value['transaction_amount']);
                                    else $db = rupiah($value['transaction_amount']);
                                    if(!$value['is_sales']) $inv = '';

                                    if($value['transaction_date'] == '1970-01-01') $value['transaction_date'] = date('Y-m-d');
                                ?>
                                <tr>
                                    <input type="hidden" name="account_statement_id" value="{{ $value['parent_statement_id'] }}">
                                    <input type="hidden" name="parent_statement_id" value="{{ $value['parent_statement_id'] }}">
                                    <input type="hidden" name="transaction_type" value="{{ $value['transaction_type'] }}">
                                    <input type="hidden" name="transaction_date" value="{{ $value['transaction_date'] }}">
                                    <input type="hidden" name="transaction_amount" value="{{ $value['transaction_amount'] }}">
                                    <input type="hidden" name="note" value="{{ $value['note'] }}">
                                    <input type="hidden" name="is_sales" value="{{ $value['is_sales'] }}">

                                    <td>{{ $key+1 }}</td>
                                    <td class="inv cursor-pointer">{!! $inv !!}</td>
                                    <td>{{ $value['transaction_date'] }}</td>
                                    <td>{{ $db }}</td>
                                    <td>{{ $cr }}</td>
                                    <td>{{ $value['note'] }}</td>
                                    <td class="processed" status="WAITING_CHECK"><code>WAITING_CHECK</code></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <!-- .row -->
@endsection
