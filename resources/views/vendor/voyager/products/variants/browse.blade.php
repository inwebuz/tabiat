@extends('voyager::master')

@section('page_title', 'Варианты товара')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-lab"></i> Варианты товара
        </h1>
        @can('add', app('App\ProductVariant'))
            <a href="{{ route('voyager.products.variants.create', $product->id) }}" class="btn btn-success btn-add-new">
                <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
            </a>
        @endcan
    </div>
@stop

@section('content')
    <div class="container-fluid" style="margin-bottom: 20px;">
        <a href="{{ route('voyager.products.edit', $product->id) }}" class="btn btn-sm btn-info m-5">
            <i class="voyager-angle-left"></i>
            <span class="hidden-xs hidden-sm">Продукт</span>
        </a>
        <a href="{{ route('voyager.products.attributes.edit', $product->id) }}" class="btn btn-sm btn-info m-5">
            <i class="voyager-categories"></i>
            <span class="hidden-xs hidden-sm">Атрибуты</span>
        </a>
    </div>
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">

                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Название</th>
                                        <th>Артикул</th>
                                        <th>Цена</th>
                                        <th>Цена со скидкой</th>
                                        <th>В наличии</th>
                                        <th>Вариант активен</th>
                                        <th class="actions text-right">{{ __('voyager::generic.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($variants as $variant)
                                    <tr>
                                        <td>{{ $variant->name }}</td>
                                        <td>{{ $variant->sku }}</td>
                                        <td>{{ $variant->price }}</td>
                                        <td>{{ $variant->sale_price }}</td>
                                        <td>{{ $variant->in_stock }}</td>
                                        <td>{{ $variant->status ? 'Да' : 'Нет' }}</td>
                                        <td class="no-sort no-click bread-actions">
                                            <a href="{{ route('voyager.products.variants.edit', ['product' => $product->id, 'variant' => $variant->id]) }}" title="Изменить" class="btn btn-sm btn-primary pull-right edit">
                                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Изменить</span>
                                            </a>
                                            <a href="javascript:;" title="Удалить" class="btn btn-sm btn-danger pull-right delete" data-id="{{ $variant->id }}" id="delete-{{ $variant->id }}">
                                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Удалить</span>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('css')
    <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
@stop

@section('javascript')
    <script>
        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('voyager.products.variants.destroy', ['product' => $product->id, 'variant' => '__id']) }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });

    </script>
@stop
