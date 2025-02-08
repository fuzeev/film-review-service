<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use DateTimeImmutable;

abstract class Person
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public ?string $middleName,
        public DateTimeImmutable $birthday,
    ) {
    }

    public function getFullName(): string
    {
        if (! $this->middleName) {
            return "{$this->firstName} {$this->lastName}";
        }

        return "{$this->firstName} {$this->middleName} {$this->lastName}";
    }

    public function getShortName(): string
    {
        if (! $this->middleName) {
            return mb_substr($this->firstName, 0, 1) . '. ' . $this->lastName;
        }

        return mb_substr($this->firstName, 0, 1) . '. ' . mb_substr($this->middleName, 0, 1) . '. ' . $this->lastName;
    }
}
