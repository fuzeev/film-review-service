<?php

namespace App\Domain\Enum;

enum MovieListSortType: string
{
    use GetValuesTrait;

    case ASC = 'asc';
    case DESC = 'desc';
}
