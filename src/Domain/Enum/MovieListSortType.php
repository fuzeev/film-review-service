<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum MovieListSortType: string
{
    use GetValuesTrait;

    case ASC = 'asc';
    case DESC = 'desc';
}
