*{{ setting('site.title') }} - Отклик на вакансию*
*Имя:* {{ $request->name }}
*Телефон:* {{ $request->phone }}
*Сообщение:* {{ $request->message }}
*Вакансия:* [{{ $vacancy->name }}]({{ $vacancy->url }})
