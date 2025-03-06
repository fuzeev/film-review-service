<?php

declare(strict_types=1);

namespace App\Application\Dto;

readonly class AddMovieResult
{
    public function __construct(
        public bool $success,
        public ?int $movieId,
        public ?string $error,
    ) {
    }
}
