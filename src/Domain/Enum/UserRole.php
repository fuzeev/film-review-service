<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum UserRole: string
{
    case Administrator = 'admin';
    case Reviewer = 'reviewer';
}
