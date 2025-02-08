<?php

declare(strict_types=1);

namespace App\Application\Dto;

use App\Domain\Entity\Movie;

readonly class GetMovieByIdResponse
{
    public function __construct(
        public ?Movie $movie,
    ) {
    }
}
