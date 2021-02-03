<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width" name="viewport" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <title>Отклик на вакансию</title>
</head>

<body style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #FFFFFF;">
    <h1>
        {{ setting('site.title') }} - Отклик на вакансию
    </h1>

    <p>
        <strong>Имя:</strong> {{ $form['name'] }} <br />
{{--        @if($contact->email)--}}
{{--            <strong>Email:</strong> {{ $contact->email }} <br />--}}
{{--        @endif--}}
        <strong>Телефон:</strong> {{ $form['phone'] }} <br />
        <strong>Сообщение:</strong> {{ $form['message'] }} <br />
        <strong>Вакансия:</strong> <a href="{{ $vacancy->url }}" target="_blank">{{ $vacancy->name }}</a> <br />
    </p>
</body>

</html>
