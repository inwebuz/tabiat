@if(isset($row->details->model) && isset($row->details->type))
    @if(class_exists($row->details->model))
        @php $relationshipField = $row->field; @endphp
            <select
                class="form-control @if(isset($row->details->taggable) && $row->details->taggable == 'on') select2-taggable @else select2-ajax-product-attribute @endif"
                name="{{ $relationshipField }}[]" multiple
                data-get-items-route="{{route('voyager.' . $dataType->slug.'.relationAttribute')}}"
                data-get-items-field="{{$row->field}}"
                @if(isset($row->details->taggable) && $row->details->taggable == 'on')
                    data-route="{{ route('voyager.'.$row->details->table.'.store') }}"
                    data-label="{{$row->details->label}}"
                    data-error-message="{{__('voyager::bread.error_tagging')}}"
                @endif
            >

                    @php
                        $selected_values = isset($dataTypeContent) ? $dataTypeContent->belongsToMany($row->details->model, $row->details->pivot_table)->get()->map(function ($item, $key) use ($row) {
                            return $item->{$row->details->key};
                        })->all() : array();
                        $relationshipOptions = app($row->details->model)->with('attribute')->get();
                        // $relationshipOptions = app($row->details->model)->whereIn('id', array_values($selected_values))->get();
                    @endphp

                    @if(!$row->required)
                        <option value="">{{__('voyager::generic.none')}}</option>
                    @endif

                    @foreach($relationshipOptions as $relationshipOption)
                        <option value="{{ $relationshipOption->{$row->details->key} }}" @if(in_array($relationshipOption->{$row->details->key}, $selected_values)){{ 'selected="selected"' }}@endif data-attribute-id="{{ $relationshipOption->attribute->id }}" data-attribute-name="{{ $relationshipOption->attribute->name }}" >{{ $relationshipOption->{$row->details->label} }}</option>
                    @endforeach

            </select>

    @else

        cannot make relationship because {{ $row->details->model }} does not exist.

    @endif

@endif

        @php
        // dump($row);
        // dump($dataType);
        // dump($dataTypeContent);
        // dd('end');
        @endphp
