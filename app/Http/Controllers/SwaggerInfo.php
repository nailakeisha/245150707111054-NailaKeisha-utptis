<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'UTP TIS - Ecommerce API',
    version: '1.0.0',
    description: 'API sederhana ecommerce menggunakan Laravel dengan JSON storage. Dibuat untuk UTP TIS.',
    contact: new OA\Contact(
        name: 'Naila Keisha',
        email: 'naila@example.com'
    )
)]
#[OA\Server(
    url: 'http://127.0.0.1:8000',
    description: 'Local Development Server'
)]
class SwaggerInfo {}