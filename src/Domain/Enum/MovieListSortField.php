<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum MovieListSortField: string
{
    use GetValuesTrait;

    case RATING = 'rating';
    case TITLE = 'title';
    case ID = 'id';
    case YEAR = 'year';
}
