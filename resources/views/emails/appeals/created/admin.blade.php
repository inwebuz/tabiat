@component('mail::message')
# Виртуальная приемная: Новое обращение
Номер: {{ $appeal->id }}

@component('mail::button', ['url' => route('voyager.appeals.show', $appeal->id)])
Посмотреть
@endcomponent

{{ config('app.name') }}
@endcomponent
