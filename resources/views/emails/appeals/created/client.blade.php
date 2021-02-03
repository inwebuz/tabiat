@component('mail::message')
# {{ __('main.appeal_message_success') }}
Номер: {{ $appeal->id }}

Код проверки: {{ $appeal->security_code }}

@component('mail::button', ['url' => route('appeals.show', ['appeal' => $appeal->id, 'security_code' => $appeal->security_code])])
{{ __('main.to_view') }}
@endcomponent

{{ config('app.name') }}
@endcomponent
