<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Enum\UserRole;
use DateTimeImmutable;

class User
{
    use PersonTrait;

    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public ?string $middleName,
        public DateTimeImmutable $birthday,
        public string $email,
        public string $username,
        public UserRole $role,
    ) {
    }
}
