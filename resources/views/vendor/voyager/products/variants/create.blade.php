@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Варианты товара')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-lab"></i>
        Создать вариант товара - {{ $product->name }}
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">

                    @if(!$productVariantAttributes->isEmpty())
                        <!-- form start -->
                        <form role="form"
                              class="form-edit-add"
                              action="{{ route('voyager.products.variants.store', $product->id) }}"
                              method="POST" enctype="multipart/form-data">

                            <!-- CSRF TOKEN -->
                            {{ csrf_field() }}

                            @include('voyager::products.variants.partials.form')

                        </form>
                    @else
                        <div class="panel-body">
                            <span>Сначала нужно добавить </span>
                            <a href="{{ route('voyager.products.attributes.edit', $product->id) }}" class="btn btn-sm btn-success m-5">
                                <i class="voyager-categories"></i>
                                <span class="hidden-xs hidden-sm">Атрибуты</span>
                            </a>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(function () {
            $('.toggleswitch').bootstrapToggle();
        });
    </script>
@stop
