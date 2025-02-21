<?php

declare(strict_types=1);

namespace App\Application\Dto;

use App\Domain\Entity\Movie;

readonly class AddMovieResult
{
    public function __construct(
        public bool $success,
        public ?int $movieId,
        public ?string $error,
    ) {
    }
}
