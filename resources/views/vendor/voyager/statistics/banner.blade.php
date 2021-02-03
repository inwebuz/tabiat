@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Баннер')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-bar-chart"></i>
        Баннер
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">

                    <div class="panel-body">
                        <h4>Баннер</h4>
                        <p>Статистика от <strong class="text-primary">{{ $fromDate->format('d-m-Y') }}</strong> до <strong class="text-primary">{{ $toDate->format('d-m-Y') }}</strong></p>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID</th>
                                    <th>Баннер</th>
                                    <th>Картинка</th>
                                    <th>Просмотры</th>
                                    <th>Клики</th>
                                </tr>
                                <tr>
                                    <td>{{ $banner->id }}</td>
                                    <td>
                                        {{ $banner->name }} {{ $banner->type }}
                                    </td>
                                    <td>
                                        <img src="{{ $banner->img }}" alt="" class="img-responsive" style="max-width: 200px;height: auto;">
                                    </td>
                                    <td>
                                        {{ $views }}
                                    </td>
                                    <td>
                                        {{ $clicks }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script></script>
@stop
