<?php

declare(strict_types=1);

namespace App\Application\Dto;

readonly class CreateAccountResult
{
    private function __construct(
        public bool $success,
        public ?int $userId,
        public ?array $errors,
    ) {
    }

    public static function success(int $userId): self
    {
        return new self(true, $userId, null);
    }

    public static function error(array $errors): self
    {
        return new self(false, null, $errors);
    }
}
