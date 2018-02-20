@extends('layout.portal')

@section('title', $title)


@section('content')

@foreach ($list_module as $key => $module)
                    <div class="col-lg-4">
                        <div class="well panel-primary" style="cursor: pointer;"
                            onclick="window.location = '{{ base_url($module['module_link']) }}'">
                            <h1>{{ $module['module_name'] }}</h1>
                        </div>
                    </div>
@endforeach

@endsection
