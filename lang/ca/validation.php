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

    'accepted' => 'El camp :attribute ha de ser acceptat.',
    'accepted_if' => 'El camp :attribute ha de ser acceptat quan :other és :value.',
    'active_url' => 'El camp :attribute ha de ser una URL vàlida.',
    'after' => 'El camp :attribute ha de ser una data posterior a :date.',
    'after_or_equal' => 'El camp :attribute ha de ser una data posterior o igual a :date.',
    'alpha' => 'El camp :attribute només pot contenir lletres.',
    'alpha_dash' => 'El camp :attribute només pot contenir lletres, números, guions i guions baixos.',
    'alpha_num' => 'El camp :attribute només pot contenir lletres i números.',
    'any_of' => 'El camp :attribute no és vàlid.',
    'array' => 'El camp :attribute ha de ser un array.',
    'ascii' => 'El camp :attribute només pot contenir caràcters alfanumèrics d\'un sol byte i símbols.',
    'before' => 'El camp :attribute ha de ser una data anterior a :date.',
    'before_or_equal' => 'El camp :attribute ha de ser una data anterior o igual a :date.',
    'between' => [
        'array' => 'El camp :attribute ha de tenir entre :min i :max elements.',
        'file' => 'El camp :attribute ha de tenir entre :min i :max kilobytes.',
        'numeric' => 'El camp :attribute ha de ser entre :min i :max.',
        'string' => 'El camp :attribute ha de tenir entre :min i :max caràcters.',
    ],
    'boolean' => 'El camp :attribute ha de ser vertader o fals.',
    'can' => 'El camp :attribute conté un valor no autoritzat.',
    'confirmed' => 'La confirmació del camp :attribute no coincideix.',
    'contains' => 'El camp :attribute no conté un valor requerit.',
    'current_password' => 'La contrasenya és incorrecta.',
    'date' => 'El camp :attribute ha de ser una data vàlida.',
    'date_equals' => 'El camp :attribute ha de ser una data igual a :date.',
    'date_format' => 'El camp :attribute ha de coincidir amb el format :format.',
    'decimal' => 'El camp :attribute ha de tenir :decimal decimals.',
    'declined' => 'El camp :attribute ha de ser rebutjat.',
    'declined_if' => 'El camp :attribute ha de ser rebutjat quan :other és :value.',
    'different' => 'Els camps :attribute i :other han de ser diferents.',
    'digits' => 'El camp :attribute ha de tenir :digits dígits.',
    'digits_between' => 'El camp :attribute ha de tenir entre :min i :max dígits.',
    'dimensions' => 'El camp :attribute té dimensions d\'imatge no vàlides.',
    'distinct' => 'El camp :attribute té un valor duplicat.',
    'doesnt_end_with' => 'El camp :attribute no ha d\'acabar amb cap dels següents: :values.',
    'doesnt_start_with' => 'El camp :attribute no ha de començar amb cap dels següents: :values.',
    'email' => 'El camp :attribute ha de ser una adreça de correu electrònic vàlida.',
    'ends_with' => 'El camp :attribute ha d\'acabar amb un dels següents: :values.',
    'enum' => 'L\'opció seleccionada per :attribute no és vàlida.',
    'exists' => 'L\'opció seleccionada per :attribute no és vàlida.',
    'extensions' => 'El camp :attribute ha de tenir una de les següents extensions: :values.',
    'file' => 'El camp :attribute ha de ser un fitxer.',
    'filled' => 'El camp :attribute ha de tenir un valor.',
    'gt' => [
        'array' => 'El camp :attribute ha de tenir més de :value elements.',
        'file' => 'El camp :attribute ha de ser major que :value kilobytes.',
        'numeric' => 'El camp :attribute ha de ser major que :value.',
        'string' => 'El camp :attribute ha de tenir més de :value caràcters.',
    ],
    'gte' => [
        'array' => 'El camp :attribute ha de tenir :value elements o més.',
        'file' => 'El camp :attribute ha de ser major o igual que :value kilobytes.',
        'numeric' => 'El camp :attribute ha de ser major o igual que :value.',
        'string' => 'El camp :attribute ha de tenir :value caràcters o més.',
    ],
    'hex_color' => 'El camp :attribute ha de ser un color hexadecimal vàlid.',
    'image' => 'El camp :attribute ha de ser una imatge.',
    'in' => 'L\'opció seleccionada per :attribute no és vàlida.',
    'in_array' => 'El camp :attribute ha d\'existir a :other.',
    'integer' => 'El camp :attribute ha de ser un número enter.',
    'ip' => 'El camp :attribute ha de ser una adreça IP vàlida.',
    'ipv4' => 'El camp :attribute ha de ser una adreça IPv4 vàlida.',
    'ipv6' => 'El camp :attribute ha de ser una adreça IPv6 vàlida.',
    'json' => 'El camp :attribute ha de ser una cadena JSON vàlida.',
    'list' => 'El camp :attribute ha de ser una llista.',
    'lowercase' => 'El camp :attribute ha d\'estar en minúscules.',
    'lt' => [
        'array' => 'El camp :attribute ha de tenir menys de :value elements.',
        'file' => 'El camp :attribute ha de ser menor que :value kilobytes.',
        'numeric' => 'El camp :attribute ha de ser menor que :value.',
        'string' => 'El camp :attribute ha de tenir menys de :value caràcters.',
    ],
    'lte' => [
        'array' => 'El camp :attribute no ha de tenir més de :value elements.',
        'file' => 'El camp :attribute ha de ser menor o igual que :value kilobytes.',
        'numeric' => 'El camp :attribute ha de ser menor o igual que :value.',
        'string' => 'El camp :attribute ha de tenir :value caràcters o menys.',
    ],
    'mac_address' => 'El camp :attribute ha de ser una adreça MAC vàlida.',
    'max' => [
        'array' => 'El camp :attribute no pot tenir més de :max elements.',
        'file' => 'El camp :attribute no pot ser major que :max kilobytes.',
        'numeric' => 'El camp :attribute no pot ser major que :max.',
        'string' => 'El camp :attribute no pot tenir més de :max caràcters.',
    ],
    'max_digits' => 'El camp :attribute no pot tenir més de :max dígits.',
    'mimes' => 'El camp :attribute ha de ser un fitxer del tipus: :values.',
    'mimetypes' => 'El camp :attribute ha de ser un fitxer del tipus: :values.',
    'min' => [
        'array' => 'El camp :attribute ha de tenir com a mínim :min elements.',
        'file' => 'El camp :attribute ha de tenir com a mínim :min kilobytes.',
        'numeric' => 'El camp :attribute ha de ser com a mínim :min.',
        'string' => 'El camp :attribute ha de tenir com a mínim :min caràcters.',
    ],
    'min_digits' => 'El camp :attribute ha de tenir com a mínim :min dígits.',
    'missing' => 'El camp :attribute no ha d\'estar present.',
    'missing_if' => 'El camp :attribute no ha d\'estar present quan :other és :value.',
    'missing_unless' => 'El camp :attribute no ha d\'estar present llevat que :other sigui :value.',
    'missing_with' => 'El camp :attribute no ha d\'estar present quan :values està present.',
    'missing_with_all' => 'El camp :attribute no ha d\'estar present quan :values estan presents.',
    'multiple_of' => 'El camp :attribute ha de ser múltiple de :value.',
    'not_in' => 'L\'opció seleccionada per :attribute no és vàlida.',
    'not_regex' => 'El format del camp :attribute no és vàlid.',
    'numeric' => 'El camp :attribute ha de ser un número.',
    'password' => [
        'letters' => 'El camp :attribute ha de contenir com a mínim una lletra.',
        'mixed' => 'El camp :attribute ha de contenir com a mínim una lletra majúscula i una minúscula.',
        'numbers' => 'El camp :attribute ha de contenir com a mínim un número.',
        'symbols' => 'El camp :attribute ha de contenir com a mínim un símbol.',
        'uncompromised' => 'El :attribute proporcionat ha aparegut en una filtració de dades. Si us plau, tria un :attribute diferent.',
    ],
    'present' => 'El camp :attribute ha d\'estar present.',
    'present_if' => 'El camp :attribute ha d\'estar present quan :other és :value.',
    'present_unless' => 'El camp :attribute ha d\'estar present llevat que :other sigui :value.',
    'present_with' => 'El camp :attribute ha d\'estar present quan :values està present.',
    'present_with_all' => 'El camp :attribute ha d\'estar present quan :values estan presents.',
    'prohibited' => 'El camp :attribute està prohibit.',
    'prohibited_if' => 'El camp :attribute està prohibit quan :other és :value.',
    'prohibited_if_accepted' => 'El camp :attribute està prohibit quan :other és acceptat.',
    'prohibited_if_declined' => 'El camp :attribute està prohibit quan :other és rebutjat.',
    'prohibited_unless' => 'El camp :attribute està prohibit llevat que :other estigui a :values.',
    'prohibits' => 'El camp :attribute prohibeix que :other estigui present.',
    'regex' => 'El format del camp :attribute no és vàlid.',
    'required' => 'El camp :attribute és obligatori.',
    'required_array_keys' => 'El camp :attribute ha de contenir entrades per: :values.',
    'required_if' => 'El camp :attribute és obligatori quan :other és :value.',
    'required_if_accepted' => 'El camp :attribute és obligatori quan :other és acceptat.',
    'required_if_declined' => 'El camp :attribute és obligatori quan :other és rebutjat.',
    'required_unless' => 'El camp :attribute és obligatori llevat que :other estigui a :values.',
    'required_with' => 'El camp :attribute és obligatori quan :values està present.',
    'required_with_all' => 'El camp :attribute és obligatori quan :values estan presents.',
    'required_without' => 'El camp :attribute és obligatori quan :values no està present.',
    'required_without_all' => 'El camp :attribute és obligatori quan cap dels :values estan presents.',
    'same' => 'El camp :attribute ha de coincidir amb :other.',
    'size' => [
        'array' => 'El camp :attribute ha de contenir :size elements.',
        'file' => 'El camp :attribute ha de ser de :size kilobytes.',
        'numeric' => 'El camp :attribute ha de ser :size.',
        'string' => 'El camp :attribute ha de tenir :size caràcters.',
    ],
    'starts_with' => 'El camp :attribute ha de començar amb un dels següents: :values.',
    'string' => 'El camp :attribute ha de ser una cadena de text.',
    'timezone' => 'El camp :attribute ha de ser una zona horària vàlida.',
    'unique' => 'El :attribute ja està en ús.',
    'uploaded' => 'El :attribute no s\'ha pogut pujar.',
    'uppercase' => 'El camp :attribute ha d\'estar en majúscules.',
    'url' => 'El camp :attribute ha de ser una URL vàlida.',
    'ulid' => 'El camp :attribute ha de ser un ULID vàlid.',
    'uuid' => 'El camp :attribute ha de ser un UUID vàlid.',


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
            'rule-name' => 'missatge-personalitzat',
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

    'attributes' => [],

];
