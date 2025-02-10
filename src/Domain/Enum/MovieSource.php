<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum MovieSource: string
{
    case IMDB = 'imdb';
    case MANUAL = 'manual';
}
