<?php

declare(strict_types=1);

namespace App\Application\Dto;

readonly class AddMovieResult
{
    private function __construct(
        public bool $success,
        public ?int $movieId,
        public ?array $errors,
    ) {
    }

    public static function success(int $movieId): self
    {
        return new self(true, $movieId, null);
    }

    public static function error(array $errors): self
    {
        return new self(false, null, $errors);
    }
}
