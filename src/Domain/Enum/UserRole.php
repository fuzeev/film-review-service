<?php

namespace App\Domain\Enum;

enum UserRole: string
{
    case Administrator = 'admin';
    case Reviewer = 'reviewer';
}
