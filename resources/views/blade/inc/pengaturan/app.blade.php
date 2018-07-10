@extends('layout.default')

@section('title', $title)

@section('load_css')
@parent
        <link href="{{ base_url('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ base_url('plugins/bower_components/switchery/dist/switchery.min.css') }}" rel="stylesheet" />
@endsection

@section('load_js')
@parent
        <script src="{{ base_url('plugins/bower_components/blockUI/jquery.blockUI.js') }}"></script>
        <!-- Sweet-Alert  -->
        <script src="{{ base_url('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/sweetalert/jquery.sweet-alert.custom.js')}}"></script>
        <script src="{{ base_url('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
        <script src="{{ base_url('js/validator.js') }}"></script>
        <script src="{{ base_url('js/module/pengaturan/app.js') }}"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@endsection

@section('content')

            <script type="text/javascript">
                document.app.data_pengaturan = {!! json_encode($settings) !!}
            </script>

            <div class="row">
                <div class="col-md-12 white-box">
                    <h4>Pengaturan pada <b>{{ $franchise->name }}</b></h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-md-offset-3 white-box" id="filterSection">
                    <form id="settingsForm" data-toggle="validator" data-delay="100">
                        <h2>Pengaturan Sistem</h2>
                        <br>
                        <div class="form-group">
                            <label class="control-label" style="margin-right: 10px;">Fungsi Penugasan Pesanan</label>
                            <input type="checkbox" name="ASSIGNED_TO_CS" value="1" checked class="js-switch" data-color="#99d683">
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="btnSettingsForm" type="button" class="btn btn-info form-control">Simpan</button>
                            </div>
                        </row>
                    </div>
                </form>
            </div>

@endsection
