@extends('layout.portal')

@section('title', $title)


@section('content')
            <div class="row bg-title">
                <!-- .page title -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Portal</h4> </div>
                <!-- /.page title -->
            </div>

            @foreach ($list_module as $key => $module)
                <div class="col-lg-4">
                    <div class="well panel-primary" style="cursor: pointer;"
                        onclick="window.location = '{{ base_url($module['module_link']) }}'">
                        <h1>{{ $module['module_name'] }}</h1>
                    </div>
                </div>
            @endforeach

            <!-- .row -->
            <!-- .row -->
@endsection
