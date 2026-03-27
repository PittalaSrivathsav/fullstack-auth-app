<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cors extends BaseConfig
{
    public array $default = [
        'allowedOrigins' => ['*'],
        'allowedHeaders' => ['*'],
        'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'exposedHeaders' => [],
        'maxAge' => 3600,
        'supportsCredentials' => false,
    ];
}