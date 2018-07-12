@extends('layout.default')

@section('title', $title)

@section('load_css')
@parent

@endsection

@section('load_js')
@parent
        <script src="{{ base_url('plugins/bower_components/blockUI/jquery.blockUI.js') }}"></script>
        <script src="{{ base_url('js/module/logistik/inventory.js') }}" type="text/javascript"></script>
@endsection

@section('header')
@include('main-inc.default.top_navigation')
@include('main-inc.default.logistik_sidebar')
@endsection

@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Stok Barang</h4></div>
                <!-- /.page title -->
            </div>

            <div class="row">
                    <div class="col-sm-12">
                        <div class="row row-in">
                            @foreach ($stock_product as $key => $value)
                            <?php $value->current_stock = (int) $value->current_stock; ?>
                            <div class="col-lg-4 col-sm-6" onclick="document.location = '{{ site_url('inventory/manage/product/'.$value->product_id) }}'">
                                <div class="white-box box-product">
                                    <ul class="col-in">
                                        <li class="col-last">
                                            <h3 class="counter text-right m-t-15">{{ $value->current_stock }}</h3>
                                        </li>
                                        <li>
                                            <h4>{{ $value->name }}</h4>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

@endsection
