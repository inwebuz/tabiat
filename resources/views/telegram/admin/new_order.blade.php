*{{ setting('site.title') }} - Новый заказ*
ID: {{ $order->id }}
Имя: {{ $order->name }}
Телефон: {{ $order->phone_number }}
E-mail: {{ $order->email }}
Адрес: {{ $order->address }}
Сообщение: {{ $order->message }}
{{-- Тип заказа: {{ $order->type_title }} --}}
Продукты:
@foreach($order->items as $item)
[{{ $item->quantity }} x {{ $item->name }}]({{ $item->product->url }}) - {{ Helper::formatPrice($item->total) }}
@endforeach
Итого: {{ Helper::formatPrice($order->total) }}

[Детали]({{ $url }})
