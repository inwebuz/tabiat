*{{ setting('site.title') }} - Контактная форма*
*Имя:* {{ $contact->name }}
*Телефон:* {{ $contact->phone }}
*Сообщение:* {{ $contact->message }}
@if($product)
*Товар:* [{{ $product->name }}]({{ $product->url }})
@endif
