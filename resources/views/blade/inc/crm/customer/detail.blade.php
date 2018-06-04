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
        <script src="{{ base_url('js/validator.js') }}"></script>
        <script src="{{ base_url('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

        <script src="{{ base_url('js/module/crm/customer_detail.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.crm_sidebar')
@endsection

@section('content')
            <script type="text/javascript">
                customer_data = {!! json_encode($customer) !!}
            </script>

            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box bg-theme m-b-0 p-b-0 mailbox-widget">
                        <h2 class="text-white p-b-20">{{ $customer->full_name }}</h2>
                        <ul class="nav customtab nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#info-pribadi" role="tab" data-toggle="tab" aria-expanded="true">
                                    <span class="visible-xs"><i class="fa fa-user"></i></span>
                                    <span class="hidden-xs">INF0 PRIBADI</span>
                                </a>
                            </li>
                            <li role="presentation" class="">
                                <a href="#profile1" role="tab" data-toggle="tab" aria-expanded="false">
                                    <span class="visible-xs"><i class="fa fa-truck"></i></span>
                                    <span class="hidden-xs">ALAMAT PENGIRIMAN</span>
                                </a>
                            </li>
                            <li role="presentation" class="">
                                <a href="#messages1" role="tab" data-toggle="tab" aria-expanded="false">
                                    <span class="visible-xs"><i class="ti-panel"></i></span>
                                    <span class="hidden-xs">SPAM</span>
                                </a>
                            </li>
                            <li role="presentation" class="">
                                <a href="#settings1" role="tab" data-toggle="tab" aria-expanded="false">
                                    <span class="visible-xs"><i class="ti-trash"></i></span>
                                    <span class="hidden-xs">DELETED</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="white-box p-0">
                        <div class="tab-content m-t-0">
                            <div role="tabpanel" class="tab-pane fade active in" id="info-pribadi">
                                <div class="row">
                                    <div class="col-md-6 p-30">
                                        <form id="infoPribadiForm" data-toggle="validator" data-delay="100" class="p-30">
                                            <input id="customer_id" type="hidden" name="customer_id" value="{{ $customer->customer_id }}">
                                            <div class="form-group">
                                                <label for="recipient-name" class="control-label">Nama</label>
                                                <input type="text" class="form-control" name="full_name"
                                                    value="{{ $customer->full_name }}"
                                                    data-error="Hmm, nama harap diisi" required>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="recipient-name" class="control-label">Email</label>
                                                <input type="email" class="form-control" name="email" value="{{ $customer->email }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="recipient-name" class="control-label">Jenis Kelamin</label>
                                                <select class="form-control" name="gender">
                                                    <option value="N" {{ ($customer->gender=='N')?'selected':'' }}>Tidak ada</option>
                                                    <option value="L" {{ ($customer->gender=='L')?'selected':'' }}>Laki-laki</option>
                                                    <option value="P" {{ ($customer->gender=='P')?'selected':'' }}>Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="recipient-name" class="control-label">Tanggal Lahir</label>
                                                <input type="text" class="form-control" name="birthdate" id="datepicker-autoclose" placeholder="yyyy-mm-dd" value="{{ $customer->birthdate }}">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4"></div>
                                                <div class="col-md-4">
                                                    <button id="btnResetInfoPribadi" type="button" class="btn btn-warning form-control">Reset</button>
                                                </div>
                                                <div class="col-md-4">
                                                    <button id="btnSaveInfoPribadi" type="button" class="btn btn-primary form-control">Simpan</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-6 p-30">
                                        <h3>Nomor HP <button onclick="addPhoneNumber()" style="margin-left: 4px;" type="button" class="btn btn-success btn-circle btn-sm m-r-5"><i class="ti-plus"></i></button></h3>
                                        <table class="table">
                                                <tbody>
                                                    @foreach ($phone_number as $key => $value)
                                                        <?php
                                                        $primary_active = '';
                                                        $primary_checked = '';
                                                        if($value->is_primary)
                                                        {
                                                            $primary_active = 'active';
                                                            $primary_checked = 'checked';
                                                        }
                                                        ?>
                                                        <tr class="advance-table-row {{ $primary_active }}">
                                                            <td style="width: 10px;"></td>
                                                            <td style="width: 40px;">
                                                                <div class="checkbox checkbox-circle checkbox-info">
                                                                    <input class="checkPrimary" value="{{ $value->customer_phonenumber_id }}" type="checkbox" {{ $primary_checked }}>
                                                                    <label></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <b>{{ $value->phonenumber }}</b>
                                                                @if(!$value->is_primary)
                                                                    <button onclick="delPhoneNumber({{ $value->customer_phonenumber_id }})" type="button" class="btn btn-danger btn-outline btn-circle btn-sm m-r-5 pull-right"><i class="icon-trash"></i></button>
                                                                @endif
                                                                <button onclick="updPhoneNumber({{ $value->customer_phonenumber_id }})" type="button" class="btn btn-warning btn-outline btn-circle btn-sm m-r-5 pull-right"><i class="ti-pencil-alt"></i></button>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="7" class="sm-pd"></td>
                                                            <tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="profile1">
                                <div class="col-md-6">
                                    <h3>Lets check profile</h3>
                                    <h4>you can use it with the small code</h4>
                                </div>
                                <div class="col-md-5 pull-right">
                                    <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a.</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="messages1">
                                <div class="col-md-6">
                                    <h3>Come on you have a lot message</h3>
                                    <h4>you can use it with the small code</h4>
                                </div>
                                <div class="col-md-5 pull-right">
                                    <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a.</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="settings1">
                                <div class="col-md-6">
                                    <h3>Just do Settings</h3>
                                    <h4>you can use it with the small code</h4>
                                </div>
                                <div class="col-md-5 pull-right">
                                    <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a.</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- .row -->

            <div class="modal fade" id="phoneNumberModal" role="dialog" aria-labelledby="exampleModalLabel1"
            style="z-index: 1041 !important;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel1">Phone Number Customer</h4> </div>
                        <div class="modal-body">
                            <form id="phoneNumberForm" data-toggle="validator" data-delay="100">
                                <input type="hidden" name="customer_phonenumber_id">
                                <input type="hidden" name="customer_id">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Nomor Handphone</label>
                                    <input type="text" class="form-control" name="phonenumber" data-error="Hmm, Nomor Handphone harap diisi" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button id="btnSavePhoneNumber" type="button" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
