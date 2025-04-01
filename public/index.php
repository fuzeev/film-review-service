<?php

declare(strict_types=1);

use App\Kernel;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return function (array $context) {
    error_reporting(E_ALL & ~E_DEPRECATED); //в пакете с refresh токенами есть deprecated код с php 8.4

    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
