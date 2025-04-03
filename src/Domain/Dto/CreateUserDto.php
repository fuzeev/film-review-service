<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use App\Domain\Enum\UserRole;
use DateTimeImmutable;

readonly class CreateUserDto
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public ?string $middleName,
        public DateTimeImmutable $birthday,
        public string $email,
        public string $username,
        public UserRole $role,
        public string $password,
    ) {
    }
}
