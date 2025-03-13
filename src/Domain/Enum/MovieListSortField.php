<?php

namespace App\Domain\Enum;

enum MovieListSortField: string
{
    use GetValuesTrait;

    case RATING = 'rating';
    case TITLE = 'title';
    case ID = 'id';
    case YEAR = 'year';
}
