@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Статистика баннеров')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-bar-chart"></i>
        Статистика баннеров
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">

                    <form role="form" action="{{ route('voyager.statistics.banner') }}" method="GET" enctype="multipart/form-data">

                        <div class="panel-body">
                            <div class="form-group">
                                <label for="banner_id">Выберите баннер</label>
                                <select name="banner_id" id="banner_id" class="form-control">
                                    @foreach($banners as $banner)
                                        <option value="{{ $banner->id }}">{{ $banner->name }} - {{ $banner->type }} @if($banner->company) (Компания: {{ $banner->company->long_name }}) @endif</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-grop">
                                <label for="">Диапазон от и до</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control onlydatepicker" name="from" >
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control onlydatepicker" name="to" >
                                    </div>
                                </div>
                            </div>
                        </div><!-- panel-body -->



                        <div class="panel-footer">
                            <button class="btn" type="submit">Показать</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script></script>
@stop
