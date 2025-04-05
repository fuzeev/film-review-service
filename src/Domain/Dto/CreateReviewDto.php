<?php

namespace App\Domain\Dto;

use App\Domain\Entity\Movie;
use App\Domain\Entity\User;

readonly class CreateReviewDto
{
    public function __construct(
        public User $author,
        public Movie $movie,
        public string $title,
        public string $text,
        public int $rating,
    ) {
    }
}
