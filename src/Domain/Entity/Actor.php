<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use DateTimeImmutable;

class Actor
{
    use PersonTrait;

    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public ?string $middleName,
        public DateTimeImmutable $birthDate,
    ) {
    }
}
