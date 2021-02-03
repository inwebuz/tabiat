@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Статистика')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-bar-chart"></i>
        Статистика
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">

                    <div class="panel-body">
                        <a href="{{ route('voyager.statistics.banners') }}">Баннеры</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script></script>
@stop
