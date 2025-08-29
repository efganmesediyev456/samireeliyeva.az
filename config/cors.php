<?php
return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'], // ehtiyac olduqda buraya frontend url-lərini əlavə et

    'allowed_methods' => ['*'], // Bütün metodlara icazə

    'allowed_origins' => ['*'], // Bütün domenlərə icazə (security üçün dəyiş)

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Bütün header-lara icazə

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // əgər cookie göndərilirsə bunu `true` et
];
