<?php
declare(strict_types=1);

namespace App\Domain\Entity;
use App\Domain\Enum\UserRole;
use DateTimeImmutable;

class User extends Person
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public ?string $middleName,
        public DateTimeImmutable $birthDate,
        public string $email,
        public string $username,
        public UserRole $role,
    ) {}
}