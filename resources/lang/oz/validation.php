<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => ':attribute massiv bo‘lishi lozim',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'captcha' => ':attribute noto‘g‘ri.',
    'confirmed' => ':attribute noto‘g‘ri tasdiqlangan.',
    'company_owner' => 'Kompaniyani tanlang.',
    'current_password' => 'Parol noto‘g‘ri',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => ':attribute noto‘g‘ri',
    'ends_with' => 'The :attribute must end with one of the following: :values',
    'exists' => 'Поле ":attribute" noto‘g‘ri to‘ldirilgan.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => ':attribute rasm bo‘lishi lozim.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => '":attribute" :max katta bo‘lishi mumkin emas.',
        'file' => 'Fayl hajmi :max KB dan ko‘p bo‘lishi mumkin emas.',
        'string' => '":attribute" :max belgidan ko‘p bo‘lishi mumkin emas.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'Fayl quyidagi turlardan biri bo‘lishi kerak: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => '":attribute" :min dan kam bo‘lishi mumkin emas.',
        'file' => 'Fayl hajmi :min KB dan kam bo‘lishi mumkin emas.',
        'string' => '":attribute" :min belgidan kam bo‘lishi mumkin emas.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'Noto‘g‘ri format.',
    'numeric' => '":attribute" son bo‘lishi kerak.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'Noto‘g‘ri format.',
    'required' => '":attribute" to‘ldirilishi kerak',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'Ushbu ":attribute" band qilingan.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'address' => 'Manzil',
        'body' => 'Kontent',
        'brand_name' => 'Brend nomi',
        'business_form' => 'Tashkiliy shakli',
        'captcha' => 'Tekshiruv kodi',
        'company_id' => 'Kompaniya',
        'current_password' => 'Joriy parol',
        'description' => 'Tavsifi',
        'email' => 'E-mail',
        'fax' => 'Faks',
        'first_name' => 'Ism',
        'image' => 'Rasm',
        'landmark' => 'Yo‘naltiruvchi nuqta',
        'last_name' => 'Familiya',
        'logo' => 'Logotip',
        'message' => 'Xabar',
        'name' => 'Ism',
        'new_password' => 'Yangi parol',
        'password' => 'Parol',
        'patronymic' => 'Отчество',
        'phone' => 'Telefon',
        'phone_number' => 'Telefon raqami',
        'price' => 'Narx',
        'price_wholesale' => 'Ulgurji narx',
        'price_from' => 'Narxi ...dan',
        'price_to' => 'Narxi ...gacha',
        'salary' => 'Maosh',
        'salary_from' => 'Maosh ...dan',
        'salary_to' => 'Maosh ...gacha',
        'site' => 'Sayt',
        'title' => 'Nom',
        'transport' => 'Transport',
        'work_hours' => 'Ish jadvali',
    ],

];
