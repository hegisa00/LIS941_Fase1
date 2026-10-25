<?php

return [
    'alpha_dash' => 'El campo :attribute solo puede contener letras, números, guiones y guiones bajos.',
    'between' => [
        'numeric' => 'El campo :attribute debe estar entre :min y :max.',
        'string' => 'El campo :attribute debe tener entre :min y :max caracteres.',
    ],
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'date' => 'El campo :attribute no es una fecha válida.',
    'decimal' => 'El campo :attribute debe tener como máximo :decimal decimales.',
    'before_or_equal' => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
    'email' => 'El campo :attribute debe ser un correo electrónico válido.',
    'in' => 'El valor seleccionado en :attribute no es válido.',
    'max' => [
        'numeric' => 'El campo :attribute no puede ser mayor que :max.',
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
    ],
    'min' => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'numeric' => 'El campo :attribute debe ser un número.',
    'regex' => 'El formato de :attribute no es válido.',
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser texto.',
    'unique' => 'Ese valor de :attribute ya está registrado.',

    'password' => [
        'letters' => 'La :attribute debe contener al menos una letra.',
        'numbers' => 'La :attribute debe contener al menos un número.',
        'mixed' => 'La :attribute debe contener mayúsculas y minúsculas.',
        'symbols' => 'La :attribute debe contener al menos un símbolo.',
        'uncompromised' => 'La :attribute aparece en una filtración de datos. Elige otra.',
    ],

    'custom' => [
        'dui' => [
            'regex' => 'El DUI debe tener el formato 00000000-0.',
        ],
        'nit' => [
            'regex' => 'El NIT debe tener el formato 0000-000000-000-0.',
        ],
        'telefono' => [
            'regex' => 'El teléfono debe tener el formato 0000-0000.',
        ],
        'fecha_nacimiento' => [
            'before_or_equal' => 'Debes tener al menos 18 años para registrarte.',
        ],
        'usuario' => [
            'alpha_dash' => 'El usuario solo puede contener letras, números, guiones y guiones bajos (sin espacios).',
        ],
    ],

    'attributes' => [
        'usuario' => 'usuario',
        'login' => 'usuario o correo',
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'password_confirmation' => 'confirmación de contraseña',
        'nombre' => 'nombre completo',
        'nombre_completo' => 'nombre completo',
        'nombre_empresa' => 'nombre de la empresa',
        'apellidos' => 'apellidos',
        'dui' => 'DUI',
        'nit' => 'NIT',
        'direccion' => 'dirección',
        'telefono' => 'teléfono',
        'fecha_nacimiento' => 'fecha de nacimiento',
        'comision' => 'comisión',
        'motivo_rechazo' => 'motivo del rechazo',
    ],
];
